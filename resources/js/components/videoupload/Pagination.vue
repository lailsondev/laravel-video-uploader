<script setup lang="ts">
import NavLink from "@/components/TextLink.vue";

defineProps<{
    links: Array<{ url: string | null; label: string; active: boolean }>;
}>();
</script>

<template>
    <div id="pagination" class="flex justify-end py-2 pr-10">
        <template v-for="link in links" :key="link.label">
            <NavLink
                v-if="link.url"
                :href="link.url"
                preserve-scroll
                class="ml-4 rounded border-gray-300 !px-4 !py-2 transition duration-300 ease-in-out hover:border-gray-600 hover:bg-gray"
                :class="{
                    'border-xl border-gray-600 bg-black text-white': link.active,
                }"
                v-html="link.label"
            />
        </template>
    </div>
</template>
