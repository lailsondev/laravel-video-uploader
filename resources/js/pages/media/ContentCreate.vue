<script setup lang="ts">
  import { index as contentsIndex } from "@/routes/media/contents";
  import { create as contentsCreate } from "@/routes/media/contents";
  import { Head, useForm } from "@inertiajs/vue3";
  import InputLabel from "@/components/ui/label/Label.vue";
  import { Input } from "@/components/ui/input";
  import TextArea from "@/components/ui/textarea/Textarea.vue";
  import InputError from "@/components/InputError.vue";
  import { Select, SelectContent, SelectItem, SelectValue, SelectTrigger } from "@/components/ui/select/";

  const form = useForm({
      title: null,
      description: null,
      body: null,
      type: null,
  });

  const optionsType = [
      { label: 'Filme', value: 'MOVIE' },
      { label: 'Serie', value: 'SERIE' }
  ];

  defineOptions({
      layout: {
          breadcrumbs: [
              {
                  title: 'Conteudos',
                  href: contentsIndex()
              },
              {
                  title: 'Criar Conteudos',
                  href: contentsCreate()
              },
          ],
      },
  });
</script>

<template>
    <Head title="Criar Conteudo" />

    <div class="p-2 w-full">
        <form action="">
            <div class="w-full mb-6">
                <InputLabel for="title">Titulo</InputLabel>
                <Input
                    id="title"
                    class="mt-2"
                    type="text"
                    v-model="form.title"
                    required
                    autofocus />
                <InputError />
            </div>

            <div class="w-full mb-6">
                <InputLabel for="description">Descricao</InputLabel>
                <Input
                    id="description"
                    class="mt-2"
                    type="text"
                    v-model="form.description"
                    required
                    autofocus />
                <InputError />
            </div>

            <div class="w-full mb-6">
                <InputLabel for="body">Conteudo</InputLabel>
                <TextArea
                    id="body"
                    class="mt-2"
                    type="text"
                    v-model="form.body"
                    required
                    autofocus />
                <InputError />
            </div>

            <div class="w-full mb-6">
                <InputLabel for="type" class="mb-4">Tipo Conteudo</InputLabel>
                <Select
                    id="type"
                    class="mt-2"
                    type="text"
                    v-model="form.type"
                    required
                    autofocus >

                    <SelectTrigger>
                        <SelectValue placeholder="Selecione um tipo do conteudo."/>
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
                <InputError />
            </div>
        </form>
    </div>
</template>
