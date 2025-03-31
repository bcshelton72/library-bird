<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BookDetails from '@/Components/BookDetails.vue';

const props = defineProps({
    book: Object,
    review: Object,
});
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <ConfirmDialog></ConfirmDialog>
        <BookDetails :book="book" :review="review" />
    </AuthenticatedLayout>
</template>
