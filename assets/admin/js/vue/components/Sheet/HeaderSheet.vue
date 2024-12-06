<!--
Ce composant récupère les entête du tableau des Menus (Sheet).
-->

<script setup>
import { computed,} from 'vue'
const props = defineProps(['header'])
const header = computed(() => {
   return props.header
})

</script>

<template>
        <thead>
        <tr>
        <th class="col-2">{{ header.active }}/{{ header.active }}</th>
        <th class="col-2">{{ header.locale }}</th>
        <th>{{  header.position }}</th>
        <th>{{  header.name }}</th>
        <th>{{  header.actions }}</th>
        </tr>
        </thead>
</template>

<style>

</style>
