@props([
    'purpose' => 'rank_prediction',
    'required' => true,
    'wireModel' => 'consent',
    'label' => null,
])

@php
    $purposeConfig = config("consent.purposes.{$purpose}", []);
    $purposeName = $purposeConfig['name'] ?? 'Rank Prediction Service';
    $defaultWording = "I agree to the processing of my personal data for providing the {$purposeName}. I have read the <a href=\"".route('privacy-notice')."\" target=\"_blank\" style=\"color:var(--blue);text-decoration:underline;\">Privacy Notice</a> and <a href=\"".route('privacy-policy')."\" target=\"_blank\" style=\"color:var(--blue);text-decoration:underline;\">Privacy Policy</a>.";
    $inputLabel = $label ?? $defaultWording;
@endphp

<div class="field full" style="margin-top:12px;background:var(--bg);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px 16px;">
    <div style="display:flex;align-items:flex-start;gap:12px;">
        <input type="checkbox"
               id="consent_{{ $purpose }}"
               wire:model="{{ $wireModel }}"
               style="width:18px;height:18px;margin-top:2px;cursor:pointer;flex-shrink:0;">
        <label for="consent_{{ $purpose }}" style="font-size:13px;color:var(--text);line-height:1.5;font-weight:400;cursor:pointer;margin:0;">
            {!! $inputLabel !!}
            @if($required)
                <span style="color:var(--danger);">*</span>
            @endif
        </label>
    </div>
    @error($wireModel)
        <span class="error" style="display:block;margin-top:6px;color:var(--danger);font-size:12px;">{{ $message }}</span>
    @enderror
</div>
