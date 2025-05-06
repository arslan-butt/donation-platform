import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface Pagination {
    current_page: number;
    from: number;
    last_page: number;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

export interface PaginatedResponse<T> {
    data?: T[];
    meta: Pagination;
}

// export interface PaginatedResponse<T> {
//     data?: T[];
//     current_page: number;
//     per_page: number;
//     total: number;
//     links: {
//         url: string | null;
//         label: string;
//         active: boolean;
//     }[];
// }

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at?: string | null;
    roles?: string[];
    created_at?: string;
    updated_at?: string;
}

export interface UserFormData {
    id?: number;
    name: string;
    email: string;
    password?: string;
    password_confirmation?: string;
    avatar?: string;
    avatar?: string;
    roles?: string[];
}

export interface Category {
    id?: number;
    name: string;
    description: string;
    parent_id?: number | null;
}

export type CampaignStatus = 'draft' | 'pending_approval' | 'active' | 'completed' | 'cancelled';

export interface Campaign {
    id?: number;
    title: string;
    description: string;
    target_amount: number | string;
    initial_amount: number | string;
    total_amount?: number | string; //sum of initial_amount and total_donation_amount
    total_donation_amount?: number | string; //sum of total_donation_amount
    start_date: string;
    end_date: string;
    status: CampaignStatus;
    type: CampaignType;
    featured_image?: string | null;
    created_by?: number;
    updated_by?: number | null;
    created_at?: string;
    updated_at?: string;
    author?: Pick<User, 'id' | 'name' | 'avatar'>;
    category?: string;
    category_id: number;
    progress?: number | string;
}

export type Paginatedcampaigns = PaginatedResponse<Campaign>;
export type PaginatedCategories = PaginatedResponse<Category>;
export type PaginatedUsers = PaginatedResponse<User>;

export type BreadcrumbItemType = BreadcrumbItem;
