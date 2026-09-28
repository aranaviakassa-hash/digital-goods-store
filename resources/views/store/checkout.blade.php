<label class="flex gap-3 text-sm">
    <input
        type="checkbox"
        name="terms"
        value="1"
        required
        class="mt-1"
    >

    <span>
        I agree to the
        <a
            href="{{ route('legal.terms') }}"
            target="_blank"
            class="underline"
        >
            Terms & Conditions
        </a>,
        <a
            href="{{ route('legal.refund') }}"
            target="_blank"
            class="underline"
        >
            Refund Policy
        </a>
        and
        <a
            href="{{ route('legal.delivery') }}"
            target="_blank"
            class="underline"
        >
            Delivery Policy
        </a>.
    </span>
</label>