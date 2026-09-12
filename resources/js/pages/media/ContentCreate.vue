<script setup lang="ts">
  import { index as contentsIndex } from "@/routes/media/contents";
  import { create as contentsCreate } from "@/routes/media/contents";
  import { Head, useForm } from "@inertiajs/vue3";
  import InputLabel from "@/components/ui/label/Label.vue";
  import { Input } from "@/components/ui/input";
  import TextArea from "@/components/ui/textarea/Textarea.vue";
  import InputError from "@/components/InputError.vue";
  import { Select, SelectContent, SelectItem, SelectValue, SelectTrigger } from "@/components/ui/select/";
  import { Button } from "@/components/ui/button";
  import ContentController from "@/actions/App/Http/Controllers/Media/ContentController";
  import { ref } from "vue";

  const form = useForm({
      title: null as string | null,
      description: null as string | null,
      body: null as string | null,
      type: null as string | null,
      cover: null as File | null,
  });

  const optionsType = [
      { label: "Filme", value: "MOVIE" },
      { label: "Série", value: "SERIE" }
  ];

  const createContent = () => {
      form.post(ContentController.store.url())
  }

  const isDragged = ref(false);
  const coverImg = ref<string | null>(null);

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
                  title: "Criar Conteúdos",
                  href: contentsCreate()
              },
          ],
      },
  });
</script>

<template>
    <Head title="Criar Conteúdo" />

    <div class="p-2 w-full">
        <form v-on:submit.prevent="createContent" novalidate>
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
                :disabled="form.processing">Criar Conteúdo</Button>
        </form>
    </div>
</template>
