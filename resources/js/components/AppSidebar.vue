<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Globe, LayoutGrid, Rocket, Shield } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

import { usePermissions } from '@/composables/usePermissions';

const { isAdmin } = usePermissions();

const mainNavItems = computed<NavItem[]>(() => {
    const commonItems = [
        {
            title: 'All Campaigns',
            href: '/campaigns/all',
            icon: Globe,
        },
        // {
        //     title: 'Donate',
        //     href: '/donations',
        //     icon: HandHeart,
        // },
    ];

    if (isAdmin.value) {
        return [
            {
                title: 'Dashboard',
                href: '/dashboard',
                icon: LayoutGrid,
            },
            ...commonItems,
            {
                title: 'Admin Console',
                href: '/admin',
                icon: Shield,
                children: [
                    { title: 'Manage campaigns', href: '/admin/campaigns' },
                    { title: 'User management', href: '/admin/users' },
                    { title: 'Manage categories', href: '/admin/categories' },
                ],
            },
        ];
    }

    return [
        {
            title: 'Dashboard',
            href: '/dashboard',
            icon: LayoutGrid,
        },
        ...commonItems,
        {
            title: 'My Campaigns',
            href: '/campaigns',
            icon: Rocket,
        },
        // {
        //     title: 'My Donations',
        //     href: '/contributions',
        //     icon: BadgeDollarSign,
        // },
    ];
});

const footerNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [];
    // Add any footer items here
    return items;
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
