<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { useForm, usePage } from "@inertiajs/vue3";
import BookDetails from '@/Components/BookDetails.vue';

const props = defineProps({
    book: Object,
});

const can = usePage().props.auth.permissions;

const form = useForm({
    id: props.book.id,
    cover_image: '',
    title: props.book.title,
    first_name: props.book.author?.first_name,
    last_name: props.book.author?.last_name,
    description: props.book.description,
    publisher: props.book.publisher?.name,
    publication_date: props.book.publication_date,
    category: props.book.category?.name,
    isbn: props.book.isbn,
    page_count: props.book.page_count,
});

const change = (e) => {
    form.cover_image = e.target.files[0];
}

const submit = (bookId) => {
    form.id = bookId;
    form.post(route('book.store'));
};
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <ConfirmDialog></ConfirmDialog>
        <div class="pb-8">
            <BookDetails v-if="book.id" :book="book" />

            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-8">
                    <div class="p-6 text-gray-900">
                        <form
                            @submit.prevent="form.post(route('book.store'))"
                            class="gap-6"
                        >
                            <div>
                                <InputLabel for="cover_image" value="Cover Image" />

                                <input type="file" id="cover_image" @input="change" />

                                <InputError class="mt-2" :message="form.errors.cover_image" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="title" value="Title" />

                                <InputText
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    required
                                    autofocus
                                />

                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="first_name" value="Author First Name" />

                                <InputText
                                    id="first_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.first_name"
                                    required
                                    autofocus
                                />

                                <InputError class="mt-2" :message="form.errors.first_name" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="last_name" value="Author Last Name" />

                                <InputText
                                    id="last_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.last_name"
                                    required
                                    autofocus
                                />

                                <InputError class="mt-2" :message="form.errors.last_name" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="description" value="Book Description" />
                                <Textarea
                                    label="Book Description"
                                    v-model="form.description"
                                    class="w-3/4 h-24"
                                    required
                                />

                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="publisher" value="Publisher" />

                                <InputText
                                    id="publisher"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.publisher"
                                    required
                                    autofocus
                                />

                                <InputError class="mt-2" :message="form.errors.publisher" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="publication_date" value="Publication Date" />

                                <DatePicker
                                    id="publication_date"
                                    v-model="form.publication_date"
                                    required
                                    autofocus
                                />

                                <InputError class="mt-2" :message="form.errors.publication_date" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="category" value="Category" />

                                <InputText
                                    id="category"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.category"
                                    required
                                    autofocus
                                />

                                <InputError class="mt-2" :message="form.errors.category" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="isbn" value="ISBN" />

                                <InputText
                                    id="isbn"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.isbn"
                                    required
                                    autofocus
                                />

                                <InputError class="mt-2" :message="form.errors.isbn" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="page_count" value="Page Count" />

                                <InputText
                                    id="page_count"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.page_count"
                                    required
                                    autofocus
                                />

                                <InputError class="mt-2" :message="form.errors.page_count" />
                            </div>
                            <div>
                                <Button
                                    class="mt-4"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                    size="small"
                                    @click=(submit(book?.id))
                                >
                                    Save
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
