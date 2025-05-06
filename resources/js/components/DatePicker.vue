<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { cn } from '@/utils';
import { CalendarDate, DateFormatter, getLocalTimeZone, parseDate, today } from '@internationalized/date';
import { CalendarIcon } from 'lucide-vue-next';
import { computed } from 'vue';

const model = defineModel<string | undefined>();
const placeholder = defineModel<string>('placeholder', { default: 'Select date' });

const df = new DateFormatter('en-US', {
    month: 'long',
    year: 'numeric',
    day: 'numeric',
});

const date = computed({
    get: () => (model.value ? parseDate(model.value) : null),
    set: (value: CalendarDate | null) => {
        model.value = value?.toString();
    },
});

const currentMonth = computed(() => date.value || parseDate(today(getLocalTimeZone()).toString()));
const months = Array.from({ length: 12 }, (_, i) => new Date(currentMonth.value.year, i).toLocaleString('default', { month: 'long' }));

const years = Array.from({ length: 11 }, (_, i) => currentMonth.value.year - 5 + i);

const handleMonthChange = (v: string) => {
    if (!date.value) {
        // Initialize with current month/year if no date selected
        const current = currentMonth.value;
        date.value = new CalendarDate(current.year, Number(v), current.day);
        return;
    }
    date.value = date.value.set({ month: Number(v) });
};

// Update the year select handler
const handleYearChange = (v: string) => {
    if (!date.value) {
        const current = currentMonth.value;
        date.value = new CalendarDate(Number(v), current.month, current.day);
        return;
    }
    date.value = date.value.set({ year: Number(v) });
};
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button variant="outline" :class="cn('w-full justify-start text-left font-normal', !date && 'text-muted-foreground')">
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{ date ? df.format(date.toDate(getLocalTimeZone())) : placeholder }}
            </Button>
        </PopoverTrigger>

        <PopoverContent class="w-auto p-0">
            <div class="flex flex-col gap-4 p-3">
                <div class="flex gap-2">
                    <Select @update:model-value="handleMonthChange">
                        <SelectTrigger class="w-[140px]">
                            <SelectValue placeholder="Month" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="(month, index) in months" :key="index" :value="index + 1">
                                {{ month }}
                            </SelectItem>
                        </SelectContent>
                    </Select>

                    <Select @update:model-value="handleYearChange">
                        <SelectTrigger class="w-[100px]">
                            <SelectValue placeholder="Year" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="year in years" :key="year" :value="year">
                                {{ year }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <!-- Calendar Grid -->
                <Calendar v-model="date" :min-value="new CalendarDate(2023, 1, 1)" initial-focus />
            </div>
        </PopoverContent>
    </Popover>
</template>
