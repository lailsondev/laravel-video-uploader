<script setup lang="ts">
import Input from '@/components/ui/input/Input.vue';
import InputLabel from '@/components/ui/label/Label.vue';

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import {Head, usePage, router, useForm} from '@inertiajs/vue3';

import { ref } from 'vue';
import axios from "axios";
import VideosController from "@/actions/App/Http/Controllers/Media/VideosController";
import VideoItem from "@/components/videoupload/VideoItem.vue";

const props = defineProps({
    content: Object,
});

const form = useForm({
    name: '',
    description: '',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '' },
    { title: 'Conteúdos', href: '' },
    { title: 'Criar Conteúdo', href: '' },
];

const isDragged = ref(false);

const findVideoById = (id: string | number) => {
    return videosList.value.find((v: any) => String(v.id) === String(id));
}

const videosList = ref<any[]>([]);

const mainHandleVideos = (videos: any) => {
    Array.from(videos as FileList).forEach((video: any) => {
        axios.post(`/media/contents/${props.content.id}/videos/upload`, {name: video.name})
            .then(response => {
                const videoPayload: any = {
                    id: response.data.id,
                    name: response.data.name,
                    uploading: true,
                    uploadProgress: 0,
                    paused: false,
                    _file: video,
                    _params: { video: response.data.id, content: props.content.id },
                    _abort: null as any,
                };

                videosList.value.unshift(videoPayload);

                chunkUploadAxios(video, { video: response.data.id, content: props.content.id });
            });
    });
};

const chunkUploadAxios = async (file: File, params: { video: any, content: any }) => {
    const chunkSize = 1024 * 1024; // 1MB
    const total = file.size;
    let start = 0;
    const url = VideosController.processChunck({content: params.content, video: params.video}).url;
    const csrf = (usePage().props as any).csrf_token;

    while (start < total) {
        const item: any = findVideoById(params.video);
        if (!item) break;

        while (item.paused) {
            await new Promise(r => setTimeout(r, 300));
            if (!findVideoById(params.video)) return;
        }

        const end = Math.min(start + chunkSize, total) - 1;
        const chunk = file.slice(start, end + 1);
        const formData = new FormData();
        formData.append('file', chunk, file.name);

        const controller = new AbortController();
        item._abort = controller;

        try {
            await axios.post(url, formData, {
                headers: {
                    'Content-Range': `bytes ${start}-${end}/${total}`,
                    'X-CSRF-TOKEN': csrf,
                },
                signal: controller.signal as any,
            });
            start = end + 1;
            const progress = Math.round((start / total) * 100);
            const target: any = findVideoById(params.video);
            if (target) target.uploadProgress = progress;
            if (start >= total && target) {
                target.uploading = false;
                target.uploadProgress = 100;
            }
        } catch (e: any) {
            if (axios.isCancel(e)) {
                const cur: any = findVideoById(params.video);

                if (cur?.paused) {
                    while (cur.paused) {
                        await new Promise(r => setTimeout(r, 300));
                        if (!findVideoById(params.video)) return;
                    }
                    continue;
                }
                return;
            }
            await new Promise(r => setTimeout(r, 800));
        }
    }
};

const pauseUpload = (videoId: any) => {
    const v: any = findVideoById(videoId);
    if (!v) return;
    v.paused = true;
    v._abort?.abort();
};
const resumeUpload = (videoId: any) => {
    const v: any = findVideoById(videoId);
    if (!v) return;
    v.paused = false;
};
const cancelUpload = (videoId: any) => {
    const v: any = findVideoById(videoId);
    if (v) v._abort?.abort();
    router.delete(VideosController.destroy({content: props.content.id, video: videoId}).url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            videosList.value = videosList.value.filter((video: any) => String(video.id) !== String(videoId));
        }
    } as any);
};

</script>

<template>
    <Head title="Upload de Vídeos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
            <div class="mx-auto mb-2 mt-10 w-[80%]">
                <InputLabel
                    @dragover.prevent="isDragged = true"
                    @dragleave.prevent="isDragged = false"
                    @drop.prevent="mainHandleVideos($event.dataTransfer.files)"
                    for="photos"
                    class="bg-black-700 flex h-28 w-full items-center justify-center rounded border-2 border-dashed border-white"
                    :class="{
                        'bg-gray-900': isDragged,
                    }"
                >Clique ou arraste seus vídeos para realizar o upload</InputLabel
                >

                <Input id="photos" type="file" class="sr-only" @change="mainHandleVideos(($event.target as any).files)"/>
            </div>

            <div>
                <VideoItem
                    v-for="video of videosList"
                    :key="video.id"
                    :video="video"
                    :content="content.id"
                    @resume="resumeUpload"
                    @pause="pauseUpload"
                    @cancel="cancelUpload"
                />
            </div>

        </div>
    </AppLayout>
</template>
