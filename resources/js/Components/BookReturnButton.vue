<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { useConfirm } from "primevue/useconfirm";

const props = defineProps({
    availabilityDate: String,
    bookId: String,
});

const can = usePage().props.auth.permissions;

const confirm = useConfirm();

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
</script>

<template>
    <Button v-if="can.return_book && availabilityDate"
        @click="returnBook(bookId)" label="Return Book" size="small" type="button" />
</template>
