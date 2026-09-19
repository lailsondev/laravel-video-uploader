<script setup lang="ts">
import { index as contentsIndex } from "@/routes/media/contents";
import { create as contentsCreate } from "@/routes/media/contents";
import {Head, useForm, usePage, router} from "@inertiajs/vue3";
import InputLabel from "@/components/ui/label/Label.vue";
import { Input } from "@/components/ui/input";
import TextArea from "@/components/ui/textarea/Textarea.vue";
import InputError from "@/components/InputError.vue";
import { Select, SelectContent, SelectItem, SelectValue, SelectTrigger } from "@/components/ui/select/";
import { Button } from "@/components/ui/button";
import ContentController from "@/actions/App/Http/Controllers/Media/ContentController";
import { ref, watch } from "vue";
import { CheckCircle2, X } from "@lucide/vue";

defineProps({
    content: Object
});

const form = useForm(usePage().props.content);

const optionsType = [
    { label: "Filme", value: "MOVIE" },
    { label: "Série", value: "SERIE" }
];

const showSuccess = ref(false)
let successTimeout: ReturnType<typeof setTimeout> | null = null

const triggerSuccess = () => {
    showSuccess.value = true
    if (successTimeout) clearTimeout(successTimeout)
    successTimeout = setTimeout(() => (showSuccess.value = false), 4000)
}

// também reage a flash vindo do backend (redirect()->with('success'))
const page = usePage()
watch(() => (page.props as any).flash?.success ?? (page.props as any).success, (val: string | undefined) => {
    if (val && val.includes('Editado com Sucesso')) triggerSuccess()
    else if (val && val.includes('Content updated')) triggerSuccess()
})

const updateContent = () => {
    const data = {
        _token: usePage().props.csrf_token,
        _method: 'PUT',
        ...form.data()
    };

    router.post(ContentController.update(form.id), data, {
        preserveScroll: true,
        onSuccess: () => triggerSuccess(),
    });
}

const isDragged = ref(false);
const coverImg = ref<string | null>(form.cover ? `/storage/${form.cover}` : null);

const mainHandleImage = (image: File | null) => {
    form.cover = image ?? null;
    if (image) mountPreviewImage(image);
    else coverImg.value = null;
};

const coverHandle = (event: Event) => {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0] ?? null;
    mainHandleImage(file);
};

const coverDrop = (event: DragEvent) => {
    isDragged.value = false;
    const file = event.dataTransfer?.files[0] ?? null;
    mainHandleImage(file);
};

const mountPreviewImage = (image: File): void => {
    const reader = new FileReader();
    reader.readAsDataURL(image);
    reader.onload = (e: ProgressEvent<FileReader>): void => {
        coverImg.value = e.target?.result as string ?? null;
    };
};

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: "Conteúdos",
                href: contentsIndex()
            },
            {
                title: "Atualizar Conteúdo",
                href: contentsCreate()
            },
        ],
    },
});
</script>

<template>
    <Head title="Atualizar Conteúdo" />

    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-2 scale-95"
        enter-to-class="opacity-100 translate-y-0 scale-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0 scale-100"
        leave-to-class="opacity-0 -translate-y-2 scale-95"
    >
        <div
            v-if="showSuccess"
            class="relative flex items-center gap-3 rounded-xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-green-50 px-4 py-3.5 shadow-sm dark:border-emerald-800/40 dark:from-emerald-950/40 dark:to-green-950/30 dark:shadow-emerald-900/10"
            role="status"
            aria-live="polite"
        >
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white shadow-md shadow-emerald-500/20">
                <CheckCircle2 class="h-5 w-5" />
            </span>
            <div class="flex-1 min-w-0 text-left">
                <p class="text-sm font-semibold leading-none text-emerald-800 dark:text-emerald-200">Editado com Sucesso!</p>
                <p class="mt-1 text-xs text-emerald-600/80 dark:text-emerald-300/70">Seu conteúdo foi atualizado com sucesso.</p>
            </div>
            <span class="hidden sm:block h-8 w-px bg-emerald-200 dark:bg-emerald-800/40"></span>
            <span class="hidden sm:block text-xs font-medium text-emerald-600 dark:text-emerald-300/80">salvo ✓</span>
            <button
                type="button"
                @click="showSuccess = false"
                class="ml-1 inline-flex h-7 w-7 items-center justify-center rounded-full text-emerald-600 hover:bg-emerald-100 hover:text-emerald-800 dark:text-emerald-300 dark:hover:bg-emerald-900/40 transition"
                aria-label="Fechar mensagem"
            >
                <X class="h-4 w-4" />
            </button>
        </div>
    </Transition>

    <div class="p-2 w-full">
        <form v-on:submit.prevent="updateContent" novalidate>
            <div class="w-full mb-6">
                <InputLabel for="title">Título</InputLabel>
                <Input
                    id="title"
                    class="mt-2"
                    type="text"
                    v-model="form.title"
                    required
                    autofocus />
                <InputError :message="form.errors.title" />
            </div>

            <div class="w-full mb-6">
                <InputLabel for="description">Descrição</InputLabel>
                <Input
                    id="description"
                    class="mt-2"
                    type="text"
                    v-model="form.description"
                    required
                    autofocus />
                <InputError :message="form.errors.description" />
            </div>

            <div class="w-full mb-6">
                <InputLabel for="body">Conteúdo</InputLabel>
                <TextArea
                    id="body"
                    class="mt-2"
                    v-model="form.body"
                    required
                    autofocus />
                <InputError :message="form.errors.body" />
            </div>

            <div class="w-full mb-6">
                <InputLabel for="type" class="mb-4">Tipo Conteúdo</InputLabel>
                <Select
                    id="type"
                    class="mt-2"
                    v-model="form.type"
                    required
                >
                    <SelectTrigger>
                        <SelectValue placeholder="Selecione um tipo do conteúdo."/>
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="type of optionsType"
                            :key="type.label"
                            :value="type.value"
                        >
                            {{ type.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.type" />
            </div>

            <div class="w-full mb-6" :class="{'flex gap-2': coverImg}">
                <div
                    :class="{'w-[50%] flex items-center justify-center': coverImg}">
                    <div>
                        <InputLabel
                            v-on:dragover.prevent="isDragged = true"
                            v-on:dragleave="isDragged = false"
                            v-on:drop.prevent="coverDrop"
                            class="w-full flex justify-center items-center p-10 rounded border-2 border-dashed border-gray-500"
                            :class="{'border-gray-200': isDragged}"
                            for="cover">Selecione ou arraste e solte a imagem da capa do seu conteúdo para upload...</InputLabel>
                        <Input
                            id="cover"
                            type="file"
                            accept="image/*"
                            class="sr-only"
                            v-on:change="coverHandle"
                        />
                        <InputError :message="form.errors.cover" />
                    </div>
                </div>

                <div
                    class="w-[50%]"
                    v-if="coverImg">
                    <img
                        :src="coverImg"
                        alt="Imagem Capa"
                        class="p-2 bg-white rounded border-gray-500 shadow max-h-64 mx-auto">
                </div>
            </div>

            <Button
                type="submit"
                class="mt-8"
                variant="secondary"
                :class="{'opacity-25': form.processing}"
                :disabled="form.processing">Atualizar</Button>
        </form>
    </div>
</template>
