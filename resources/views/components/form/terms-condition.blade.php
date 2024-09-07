<div class="input-group mb-3 terms-condition">
    <input type="checkbox" id="terms-condition" name="consent" required wire:model="consent"
        class="checkbox checkbox-primary">
    <label for="terms-condition" class="checkbox-label d-flex flex-wrap">I agree to
        the&#8194;<wbr>
        <span class="d-flex" role="list">
            <a href="#" target="_blank" class="text-black" rel="noopener noreferrer"
                role="listitem">Terms&nbsp;&&nbsp;Conditions
            </a>
            <span>&#8194;and&#8194;</span>
            <a href="#" target="_blank" class="text-black" rel="noopener noreferrer">Privacy&nbsp;Policy</a>
        </span>
    </label>
    @if (!empty($feedback))
        {{ $feedback }}
    @endif
</div>
