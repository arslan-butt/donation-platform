import { computed } from 'vue';

type FormattableValue = number | string | bigint;

export function useFormatters() {
    /**
     * Format value as currency
     * @param value - The value to format
     * @param options - Intl.NumberFormat options
     * @returns Formatted currency string
     */
    const currency = (value: FormattableValue, options?: Intl.NumberFormatOptions) => {
        return computed(() => {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
                ...options,
            }).format(Number(value));
        }).value;
    };

    /**
     * Format value as number
     * @param value - The value to format
     * @param options - Intl.NumberFormat options
     * @returns Formatted number string
     */
    const number = (value: FormattableValue, options?: Intl.NumberFormatOptions) => {
        return computed(() => {
            return new Intl.NumberFormat(undefined, {
                maximumFractionDigits: 2,
                ...options,
            }).format(Number(value));
        }).value;
    };

    /**
     * Format value as percentage
     * @param value - The value to format (0-1 range)
     * @param options - Formatting options
     * @returns Formatted percentage string
     */
    const percent = (value: FormattableValue, options?: { decimals?: number }) => {
        return computed(() => {
            const numValue = Number(value);
            const multiplier = 100;
            const decimals = options?.decimals ?? 0;
            return `${numValue * multiplier.toFixed(decimals)}%`;
        }).value;
    };

    return {
        currency,
        number,
        percent,
    };
}

// Export types for external use
export type UseFormattersReturn = ReturnType<typeof useFormatters>;
