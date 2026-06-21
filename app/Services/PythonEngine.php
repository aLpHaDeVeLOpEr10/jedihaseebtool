<?php

namespace App\Services;

/**
 * PythonEngine — executes user-submitted Python code via a sandboxed subprocess.
 *
 * Security notes:
 *  - Code is written to a uniquely-named temp file and deleted immediately after.
 *  - Execution is killed after $timeout seconds.
 *  - Output is capped at $maxOutput bytes.
 *  - Process runs with the server's own user context (same as PHP), so this is
 *    suitable for a personal / development tool on a trusted machine.
 */
class PythonEngine
{
    private int    $timeout   = 10;       // seconds
    private int    $maxOutput = 65_536;   // 64 KB

    /* ── Public entry-point ────────────────────────────────────────── */

    public function execute(array $data): array
    {
        $code  = trim($data['code']  ?? '');
        $stdin = $data['stdin'] ?? '';

        if ($code === '') {
            return ['success' => false, 'error' => 'No code provided. Please write some Python code to run.'];
        }

        if (strlen($code) > 100_000) {
            return ['success' => false, 'error' => 'Code too large (max 100 KB).'];
        }

        $pythonBin = $this->findPython();
        if ($pythonBin === null) {
            return ['success' => false, 'error' => 'Python interpreter not found on this server.'];
        }

        // Write to a temp .py file
        $tmpFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR
                 . 'pyrun_' . uniqid('', true) . '.py';

        if (@file_put_contents($tmpFile, $code) === false) {
            return ['success' => false, 'error' => 'Could not write temporary file for execution.'];
        }

        try {
            return $this->runProcess($pythonBin, $tmpFile, (string) $stdin);
        } finally {
            @unlink($tmpFile);
        }
    }

    /* ── Find a working Python binary ──────────────────────────────── */

    private function findPython(): ?string
    {
        // Fast path: 'python' already in PATH (true for this server)
        $out = [];
        $ret = 0;
        exec('python --version 2>&1', $out, $ret);
        if ($ret === 0) {
            return 'python';
        }

        // Explicit Windows install paths
        $windowsPaths = [
            'C:\\Python314\\python.exe',
            'C:\\Python313\\python.exe',
            'C:\\Python312\\python.exe',
            'C:\\Python311\\python.exe',
            'C:\\Python310\\python.exe',
        ];
        foreach ($windowsPaths as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        // Unix / macOS fallback
        exec('python3 --version 2>&1', $out, $ret);
        if ($ret === 0) {
            return 'python3';
        }

        return null;
    }

    /* ── Run Python in a subprocess with timeout ───────────────────── */

    private function runProcess(string $bin, string $file, string $stdin): array
    {
        $descriptors = [
            0 => ['pipe', 'r'],   // stdin
            1 => ['pipe', 'w'],   // stdout
            2 => ['pipe', 'w'],   // stderr
        ];

        // Array form → PHP 8 handles quoting; no shell injection possible
        $process = @proc_open(
            [$bin, $file],
            $descriptors,
            $pipes,
            sys_get_temp_dir()   // cwd — keeps the process away from project root
        );

        if (! is_resource($process)) {
            return ['success' => false, 'error' => 'Failed to spawn Python process.'];
        }

        // Feed stdin then close it (signals EOF to the Python process)
        if ($stdin !== '') {
            fwrite($pipes[0], $stdin);
        }
        fclose($pipes[0]);

        // Non-blocking reads so we can implement timeout
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $stdout    = '';
        $stderr    = '';
        $start     = microtime(true);
        $timedOut  = false;
        $truncated = false;

        while (true) {
            $elapsed = microtime(true) - $start;

            // ── Timeout ──
            if ($elapsed >= $this->timeout) {
                $timedOut = true;
                proc_terminate($process);
                break;
            }

            // ── Drain available bytes ──
            $c1 = fread($pipes[1], 8_192);
            $c2 = fread($pipes[2], 8_192);
            if ($c1 !== false && $c1 !== '') $stdout .= $c1;
            if ($c2 !== false && $c2 !== '') $stderr .= $c2;

            // ── Output size cap ──
            if ((strlen($stdout) + strlen($stderr)) >= $this->maxOutput) {
                proc_terminate($process);
                $stdout   .= "\n\n[Output truncated — exceeded 64 KB limit]";
                $truncated = true;
                break;
            }

            // ── Check if process finished ──
            $status = proc_get_status($process);
            if (! $status['running']) {
                // Drain any last bytes
                $tail1 = stream_get_contents($pipes[1]);
                $tail2 = stream_get_contents($pipes[2]);
                if ($tail1) $stdout .= $tail1;
                if ($tail2) $stderr .= $tail2;
                break;
            }

            usleep(50_000); // poll every 50 ms
        }

        @fclose($pipes[1]);
        @fclose($pipes[2]);
        $exitCode = proc_close($process);   // reaps the process; returns exit code
        $ms       = (int) round((microtime(true) - $start) * 1_000);

        // Strip ANSI colour codes from both streams
        $stdout = $this->stripAnsi($stdout);
        $stderr = $this->stripAnsi($stderr);

        if ($timedOut) {
            return [
                'success'        => false,
                'output'         => $stdout,
                'error'          => "Process timed out after {$this->timeout} s — infinite loop or heavy computation?",
                'exit_code'      => -1,
                'execution_time' => $ms,
            ];
        }

        return [
            'success'        => ($exitCode === 0 && ! $truncated),
            'output'         => $stdout,
            'error'          => ($stderr !== '') ? $stderr : null,
            'exit_code'      => (int) $exitCode,
            'execution_time' => $ms,
        ];
    }

    /* ── Strip ANSI escape sequences ───────────────────────────────── */

    private function stripAnsi(string $s): string
    {
        return (string) preg_replace('/\x1b\[[0-9;]*[A-Za-z]/u', '', $s);
    }
}
