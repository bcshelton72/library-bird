<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { useConfirm } from "primevue/useconfirm";

const props = defineProps({
    bookId: String,
});

const can = usePage().props.auth.permissions;

const confirm = useConfirm();

const deleteBook = (bookId) => {
    confirm.require({
        message: 'Warning! Are you sure you want to delete this book?',
        header: 'Confirmation',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Yes Delete This Book',
            severity: 'danger'
        },
        accept: () => {
            router.delete(route("book.destroy", bookId));
        },
        reject: () => {
            //
        }
    });
};
</script>

<template>
    <Button v-if="can.delete_book"
        @click="deleteBook(bookId)" label="Delete" size="small" severity="danger" type="button" />
</template>
