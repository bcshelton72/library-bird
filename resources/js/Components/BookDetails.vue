<script setup>
import InputError from '@/Components/InputError.vue';
import { useForm, usePage } from "@inertiajs/vue3";
import BookCheckoutButton from '@/Components/BookCheckoutButton.vue';
import BookReturnButton from '@/Components/BookReturnButton.vue';
import BookUpdateButton from '@/Components/BookUpdateButton.vue';

const props = defineProps({
    book: Object,
    review: Object,
});

const can = usePage().props.auth.permissions;

const form = useForm({
    book_id: props.book.id,
    review_text: props.review?.review_text,
    rating: props.review?.rating,
});

const submit = (bookId) => {
    form.book_id = bookId;
    form.post(route('review.store'));
};
</script>

<template>
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
                        <BookCheckoutButton :available="book.available" :bookId="book.id" class="mb-2 float-right" />
                        <BookReturnButton :availabilityDate="book.availability_date" :bookId="book.id" class="mb-2 float-right" />
                        <BookUpdateButton v-if="route().current() == 'book.show'" :bookId="book.id" class="mb-2 ml-12 float-right" />
                        <Link :href="can.manage_books ? route('manage-books') : route('dashboard')">
                            <Button label="Back" size="small" class="float-right" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="can.create_review" class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-8">
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
</template>
