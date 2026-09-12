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

  const form = useForm({
      title: null,
      description: null,
      body: null,
      type: null,
  });

  const optionsType = [
      { label: "Filme", value: "MOVIE" },
      { label: "Série", value: "SERIE" }
  ];

  const createContent = () => {
      form.post(ContentController.store.url())
  }

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
        <form v-on:submit.prevent="createContent">
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

            <Button
                type="submit"
                class="mt-8"
                variant="secondary"
                :class="{'opacity-25': form.processing}"
                :disabled="form.processing">Criar Conteúdo</Button>
        </form>
    </div>
</template>