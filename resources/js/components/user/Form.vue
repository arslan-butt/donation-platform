<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { type UserFormData } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';

const { item } = defineProps<{
    item?: UserFormData & { id?: number }; // added id for update
}>();

const page = usePage();
const availableRoles = page.props.availableRoles;

const isEdit = !!item;

const form = useForm<UserFormData>({
    name: item?.name || '',
    email: item?.email || '',
    password: '',
    password_confirmation: '',
    roles: Array.isArray(item?.roles) ? item.roles[0] : item?.roles || '',
});

const submit = () => {
    if (isEdit && item?.id) {
        form.patch(route('admin.users.update', item.id), {
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.users.store'), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div class="grid gap-2">
            <Label for="name">Name</Label>
            <Input id="name" v-model="form.name" required autocomplete="name" placeholder="Name" />
            <InputError :message="form.errors.name" />
        </div>

        <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input id="email" type="email" v-model="form.email" required autocomplete="email" placeholder="Email" />
            <InputError :message="form.errors.email" />
        </div>
        <!-- Roles Multi-Select Dropdown -->
        <div class="grid gap-2">
            <Label for="roles">Role</Label>
            <Select v-model="form.roles">
                <SelectTrigger>
                    <SelectValue placeholder="Select role" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="role in availableRoles" :key="role" :value="role" :selected="form.roles.includes(role)">
                        {{ role }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <InputError :message="form.errors.roles" />
        </div>

        <div class="grid gap-2">
            <Label for="password">Password</Label>
            <Input id="password" type="password" v-model="form.password" autocomplete="new-password" placeholder="Password" />
            <InputError :message="form.errors.password" />
        </div>

        <div class="grid gap-2">
            <Label for="password_confirmation">Confirm Password</Label>
            <Input
                id="password_confirmation"
                type="password"
                v-model="form.password_confirmation"
                autocomplete="new-password"
                placeholder="Confirm Password"
            />
            <InputError :message="form.errors.password_confirmation" />
        </div>

        <div class="flex items-center gap-4">
            <Button :disabled="form.processing">
                {{ isEdit ? 'Update' : 'Create' }}
            </Button>
            <Transition
                enter-active-class="transition ease-in-out"
                enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out"
                leave-to-class="opacity-0"
            >
                <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">
                    {{ isEdit ? 'Updated.' : 'Saved.' }}
                </p>
            </Transition>
        </div>
    </form>
</template>
