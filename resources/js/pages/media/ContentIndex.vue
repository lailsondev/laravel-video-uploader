<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
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
import TextLink from "@/components/TextLink.vue";
import ContentController from "@/actions/App/Http/Controllers/Media/ContentController";
import {Pencil, Trash} from '@lucide/vue'

const props = defineProps({
    contents: Object,
});

const form = useForm({});

const removeContent = (content: string) => {
    if (!confirm('Deseja mesmo remover este conteúdo?')) return;

    form.delete(ContentController.destroy(content));
}

defineOptions({
    layout: {
        breadcrumbs: [
            { title: "Meus Conteúdos", href: contentsIndex() },
        ],
    },
});
</script>

<template>
    <Head title="Meus Conteúdos" />

    <Table>
        <TableHeader>
            <TableRow>
                <TableHead class="w-[100px]">
                    #
                </TableHead>
                <TableHead>Conteúdo</TableHead>
                <TableHead>Criado em</TableHead>
                <TableHead>
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
                <TableCell class="flex gap-x-2">
                    <TextLink :href="ContentController.edit({content: content.id})"
                              class="no-underline rounded px-4 py-2 bg-blue-700 border border-blue-900 text-white font-bold mb-2"
                    >
                        <Pencil />
                    </TextLink>

                    <TextLink @click.prevent="removeContent(content.id)"
                              class="no-underline rounded px-4 py-2 bg-red-700 border border-red-900 text-white font-bold mb-2"
                    >
                        <Trash />
                    </TextLink>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>

    <Pagination :links="contents.links" v-if="contents?.last_page > 1" />
</template>
