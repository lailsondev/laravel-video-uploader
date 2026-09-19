<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import InputLabel from '@/components/ui/label/Label.vue';

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';


import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '',
    },
    {
        title: 'Conteúdos',
        href: '',
    },
    {
        title: 'Criar Conteúdo',
        href: '',
    },
];

const isDragged = ref(false);

const form = useForm({
    name: '',
    description: '',
});

const handleDropVideos = (e) => {
    console.log(e.dataTransfer.files);
};

const handleInputVideos = (e) => {
    console.log(e.target.files);
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
                    @drop.prevent="handleDropVideos"
                    for="photos"
                    class="bg-black-700 flex h-28 w-full items-center justify-center rounded border-2 border-dashed border-white"
                    :class="{
                        'bg-gray-900': isDragged,
                    }"
                >Clique ou arraste seus vídeos para realizar o upload</InputLabel
                >

                <Input id="photos" type="file" class="sr-only" @change="handleInputVideos"/>
            </div>
            <div>

                <div class="p-6 text-gray-900" v-for="line of [...Array(5).keys()]" :key="line">
                    <div class="flex space-x-6">

                        <div class="w-full max-w-[350px] space-y-3">
                            <div class="h-[180px] w-full">
                                <img src="https://placeholdit.com/320x180/dddddd/999999" class="h-[180px] w-full rounded bg-white p-1 shadow" />
                            </div>
                            <!-- Encoding Status -->
                            <div class="space-y-1">
                                <div class="h-3 overflow-hidden rounded bg-gray-100 shadow-inner">
                                    <div
                                        class="h-full bg-green-500"
                                        style="width: 65%"
                                    ></div>
                                </div>
                                <div class="text-sm font-bold text-white">Convertendo vídeo</div>
                            </div>
                            <!-- Encoding Status -->

                            <!-- Uploading Status -->
                            <div class="space-y-1">
                                <div class="h-3 overflow-hidden rounded bg-gray-100 shadow-inner">
                                    <div
                                        class="h-full bg-blue-500"
                                        style="width: 45%"
                                    ></div>
                                </div>
                                <div class="text-sm font-bold text-white">Enviando Vídeo</div>
                            </div>
                            <!-- Uploading Status -->

                            <div class="flex items-center space-x-3">
                                <!-- Disparar os eventos -->
                                <button class="text-sm font-medium text-blue-500" >Pausar</button>
                                <button class="text-sm font-medium text-blue-500" >Continuar</button>
                                <button class="text-sm font-medium text-blue-500" >Cancelar</button>
                            </div>
                        </div>

                        <form @submit.prevent="() => {}" class="flex-grow space-y-6">
                            <div>
                                <InputLabel for="title" class="text-white">Nome Vídeo</InputLabel>

                                <Input id="title" type="text" v-model="form.name" required autofocus class="text-white" />

                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="description" class="text-white">Descrição</InputLabel>

                                <Input
                                    id="description"
                                    type="text"
                                    class="mt-1 block w-full p-2 text-white"
                                    v-model="form.description"
                                    required
                                    autofocus
                                    autocomplete=""
                                />

                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div class="mt-8">
                                <Button variant="secondary"> Atualizar </Button>
                                <span class="font-thin text-white">Atualizado...</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
