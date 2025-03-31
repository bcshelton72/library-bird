<script setup>
import { onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BookCatalog from '@/Components/BookCatalog.vue';

const props = defineProps({
    books: Object,
});

// Randomize books to feature different ones each page refresh or on button press
onMounted(() => {
    randomize();
});

const randomize = () => {
    props.books.sort((a, b) => 0.5 - Math.random());
}
</script>
<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <ConfirmDialog></ConfirmDialog>
            <div class="flex justify-between">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800"
                >
                    Welcome {{ $page.props.auth.user.name }}!
                </h2>
                <div v-if="$page.props.flash.success" class="alert alert-success">
                    <Message severity="success">{{ $page.props.flash.success }}</Message>
                </div>
                <div v-if="$page.props.flash.error" class="alert alert-danger">
                    <Message severity="error">{{ $page.props.flash.error }}</Message>
                </div>
                <Button @click="randomize" label="Surprise me!" size="small" />
            </div>
        </template>
        <div class="pb-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-8">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-xl font-semibold">Book Catalog</h2>
                        <p>Enjoy this selection of featured books or search for any title or author.</p>
                        <BookCatalog :books="books" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
