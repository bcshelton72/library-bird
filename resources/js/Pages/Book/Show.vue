<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from 'primevue/button';
import ConfirmDialog from 'primevue/confirmdialog';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Rating from 'primevue/rating';
import Tag from 'primevue/tag';
import Textarea from 'primevue/textarea';
import { router, useForm } from "@inertiajs/vue3";
import { useConfirm } from "primevue/useconfirm";


const props = defineProps({
    book: Object,
    review: Object,
});

const form = useForm({
    book_id: props.book.id,
    review_text: props.review?.review_text,
    rating: props.review?.rating,
});

const confirm = useConfirm();

const checkoutBook = (bookId) => {
    confirm.require({
        message: 'Are you sure you want to check out this book?',
        header: 'Confirmation',
        rejectProps: {
            label: 'No thanks',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Yes it looks great!'
        },
        accept: () => {
            router.put(route("book.checkout", bookId));
        },
        reject: () => {
            //
        }
    });
};

const returnBook = (bookId) => {
    confirm.require({
        message: 'Are you sure you want to return this book?',
        header: 'Confirmation',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Yes Mark As Returned'
        },
        accept: () => {
            router.put(route("book.return", bookId));
        },
        reject: () => {
            //
        }
    });
};

const submit = (bookId) => {
    form.book_id = bookId;
    form.post(route('review.store'));
};
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <ConfirmDialog></ConfirmDialog>
        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-8">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between">
                            <div>
                                <div>
                                    <img
                                        :src="book.cover_image
                                            ? '/storage/' + book.cover_image
                                            : '/storage/cover_images/default/' + book.id % 10 + '.png'"
                                        :alt="book.cover_image"
                                        class="w-24 rounded mr-2 mb-3"
                                        align="left"
                                    />
                                    <div class="text-xl font-semibold">{{ book.title }}</div>
                                    <div>{{ book.author.first_name + ' ' + book.author.last_name }}</div>
                                    <div class="mt-2"><Rating :modelValue="book.average_rating" readonly /></div>
                                </div>
                                <div class="mt-4">
                                    <div>{{ book.description }} <Tag severity="info" :value="book.category.name"></Tag></div>
                                </div>
                                <div class="mt-4 text-sm" style="clear: left;">
                                    <div>{{ book.publisher.name }} ({{ book.publication_date }})</div>
                                    <div>ISBN: {{ book.isbn }}</div>
                                    <div>Page Count: {{ book.page_count }}</div>
                                </div>
                            </div>
                            <div class="ml-10 mr-3 w-32">
                                <Button v-if="book.available && $page.props.auth.permissions.checkout_book"
                                    @click="checkoutBook(book.id)"  label="Check Out" size="small" type="button" class="mb-2 float-right" />
                                <Button v-if="book.availability_date && $page.props.auth.permissions.return_book"
                                    @click="returnBook(book.id)" label="Return Book" size="small" type="button" class="mb-2 float-right" />
                                <Link :href="route('dashboard')">
                                    <Button label="Back" size="small" class="float-right" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="$page.props.auth.permissions.create_review" class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-8">
                    <div class="p-6 text-gray-900">
                        <form
                            @submit.prevent="form.post(route('review.store'))"
                            class="gap-6"
                        >
                            <div>
                                <Textarea
                                    label="Your Review"
                                    placeholder="What did you think of this book?"
                                    v-model="form.review_text"
                                    class="w-3/4 h-24"
                                />

                                <InputError class="mt-2" :message="form.errors.review_text" />
                            </div>
                            <div>
                                <Rating v-model="form.rating" class="mt-2" />

                                <InputError class="mt-2" :message="form.errors.rating" />
                            </div>
                            <div>
                                <Button
                                    class="mt-4"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                    size="small"
                                    @click=(submit(book.id))
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
