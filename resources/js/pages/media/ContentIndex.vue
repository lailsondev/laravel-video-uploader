<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { dashboard } from "@/routes";
import { index as contentsIndex } from "@/routes/media/contents";
import Pagination from "@/components/videoupload/Pagination.vue";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'

defineProps({
    contents: Object,
});

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Meus Conteudos', href: contentsIndex() },
        ],
    },
});
</script>

<template>
    <Head title="Meus Conteudos" />

    <Table>
        <TableHeader>
            <TableRow>
                <TableHead class="w-[100px]">
                    #
                </TableHead>
                <TableHead>Conteúdo</TableHead>
                <TableHead>Criado em</TableHead>
                <TableHead class="text-right">
                    Ações
                </TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="content in contents.data" :key="content.id">
                <TableCell class="font-medium">
                    {{ content.id }}
                </TableCell>
                <TableCell>{{ content.title }}</TableCell>
                <TableCell>{{ content.created_at }}</TableCell>
                <TableCell class="text-right">

                </TableCell>
            </TableRow>
        </TableBody>
    </Table>

    <Pagination :links="contents.links" v-if="contents?.links" />
    <pre v-if="!contents?.links" class="p-4 bg-red-900 text-white text-xs">{{ JSON.stringify(contents, null, 2) }}</pre>
</template>


