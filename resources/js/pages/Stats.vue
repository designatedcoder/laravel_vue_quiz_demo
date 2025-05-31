<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Quiz Stats',
        href: '/quiz-stats',
    },
];

defineProps({
    stats: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <Head title="Quiz Stats" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                #ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Score
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Average Score
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Highest Score
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Date Played
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"
                            v-for="(item, index) in stats"
                            :key="index"
                        >
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ item.id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ item.score }}
                            </td>
                            <td class="px-6 py-4">
                                45.5
                            </td>
                            <td class="px-6 py-4">
                                90
                            </td>
                            <td class="px-6 py-4">
                                {{ item.created_at }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
