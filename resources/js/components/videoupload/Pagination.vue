<script setup lang="ts">
import NavLink from "@/components/TextLink.vue";

defineProps<{
    links: Array<{ url: string | null; label: string; active: boolean }>;
}>();
</script>

<template>
    <div id="pagination" class="flex justify-end gap-2 py-4 pr-10">
        <template v-for="link in links" :key="link.label">
            <NavLink
                v-if="link.url"
                :href="link.url"
                preserve-scroll
                class="rounded border border-gray-300 px-4 py-2 transition hover:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800"
                :class="{
                    'border-gray-600 bg-black text-white dark:bg-white dark:text-black': link.active,
                }"
            >
                <span v-html="link.label"></span>
            </NavLink>
            <span
                v-else
                class="rounded border border-gray-200 px-4 py-2 text-gray-400"
                v-html="link.label"
            />
        </template>
    </div>
</template>
