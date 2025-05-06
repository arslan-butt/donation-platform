import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function usePermissions() {
    const page = usePage();

    const can = computed(() => page.props.auth.can || {});
    const roles = computed(() => page.props.auth.user.roles || []);
    const isAdmin = computed(() => roles.value.some((role: { name: string }) => role.name === 'admin'));

    return {
        can,
        roles,
        isAdmin,
    };
}
