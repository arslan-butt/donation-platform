//components/donation/Form.vue
<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';

const props = defineProps({
    campaign: Object,
    show: Boolean,
});

const emit = defineEmits(['close']);

const form = useForm({
    amount: '',
    payment_reference: '',
    campaign_id: props.campaign?.id,
});

const submitDonation = () => {
    form.post(route('donations.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit('close');
        },
        onError: () => {
            // Error handling
        },
    });
};
</script>

<template>
    <Dialog :open="show" @update:open="emit('close')">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>Donate to {{ campaign.title }}</DialogTitle>
                <DialogDescription> Please enter the amount you would like to donate. </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submitDonation">
                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <Label for="amount">Donation Amount ($)</Label>
                        <Input
                            id="amount"
                            v-model="form.amount"
                            type="number"
                            placeholder="50.00"
                            step="0.01"
                            class="mt-1 block w-full"
                            :disabled="form.processing"
                        />
                        <InputError class="mt-2" :message="form.errors.amount" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="transaction_id">Transaction Reference</Label>
                        <Input
                            id="transaction_id"
                            v-model="form.payment_reference"
                            placeholder="Enter payment reference"
                            class="mt-1 block w-full"
                            :disabled="form.processing"
                        />
                        <InputError class="mt-2" :message="form.errors.payment_reference" />
                        <p class="text-muted-foreground mt-1 text-sm">For verification purposes</p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <Button variant="outline" type="button" @click="emit('close')" :disabled="form.processing"> Cancel </Button>
                        <Button type="submit" :disabled="form.processing">
                            <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                            Confirm Donation
                        </Button>
                    </div>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
