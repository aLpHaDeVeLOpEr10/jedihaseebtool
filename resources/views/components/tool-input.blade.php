@props(['input'])

<div class="space-y-1">
    <label for="{{ $input->field_name }}"
           class="block text-sm font-medium text-gray-700">
        {{ $input->field_label }}
        @if($input->required)
            <span class="text-red-500 ml-0.5">*</span>
        @endif
    </label>

    @if($input->field_type === 'textarea')
        <textarea
            id="{{ $input->field_name }}"
            name="{{ $input->field_name }}"
            x-model="inputs['{{ $input->field_name }}']"
            placeholder="{{ $input->placeholder }}"
            rows="4"
            {{ $input->required ? 'required' : '' }}
            class="form-input resize-y"
        >{{ $input->default_value }}</textarea>

    @elseif($input->field_type === 'select')
        <select
            id="{{ $input->field_name }}"
            name="{{ $input->field_name }}"
            x-model="inputs['{{ $input->field_name }}']"
            {{ $input->required ? 'required' : '' }}
            class="form-input">
            <option value="">-- Select --</option>
            @foreach($input->options ?? [] as $option)
                <option value="{{ $option['value'] ?? $option }}"
                    {{ ($input->default_value === ($option['value'] ?? $option)) ? 'selected' : '' }}>
                    {{ $option['label'] ?? $option }}
                </option>
            @endforeach
        </select>

    @elseif($input->field_type === 'file')
        <input
            type="file"
            id="{{ $input->field_name }}"
            name="{{ $input->field_name }}"
            @change="inputs['{{ $input->field_name }}'] = $event.target.files[0]"
            {{ $input->required ? 'required' : '' }}
            class="form-input file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100" />

    @elseif($input->field_type === 'number')
        <input
            type="number"
            id="{{ $input->field_name }}"
            name="{{ $input->field_name }}"
            x-model="inputs['{{ $input->field_name }}']"
            placeholder="{{ $input->placeholder }}"
            value="{{ $input->default_value }}"
            {{ $input->required ? 'required' : '' }}
            class="form-input" />

    @elseif($input->field_type === 'checkbox')
        <label class="flex items-center gap-2 cursor-pointer">
            <input
                type="checkbox"
                id="{{ $input->field_name }}"
                name="{{ $input->field_name }}"
                x-model="inputs['{{ $input->field_name }}']"
                {{ $input->default_value ? 'checked' : '' }}
                class="rounded border-gray-300 text-brand-600 focus:ring-brand-500" />
            <span class="text-sm text-gray-600">{{ $input->placeholder ?: $input->field_label }}</span>
        </label>

    @else
        <input
            type="text"
            id="{{ $input->field_name }}"
            name="{{ $input->field_name }}"
            x-model="inputs['{{ $input->field_name }}']"
            placeholder="{{ $input->placeholder }}"
            value="{{ $input->default_value }}"
            {{ $input->required ? 'required' : '' }}
            class="form-input" />
    @endif

    @if($input->help_text)
        <p class="text-xs text-gray-500 mt-1">{{ $input->help_text }}</p>
    @endif
</div>
