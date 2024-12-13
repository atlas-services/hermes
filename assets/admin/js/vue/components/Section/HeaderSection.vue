<!--
Ce composant récupère les entête du tableau des Sous Menus (Menu).
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
                        <th>{{ header.active }}/{{ header.inactive }}</th>
                        <th>{{ header.locale }}</th>
                        <th>{{ header.sheet }}</th>
                        <th>{{ header.menu }}</th>
                        <th>{{ header.position }}</th>
                        <th>{{ header.template }}</th>
                        <th>{{ header.post }}</th>
                        <th>{{ header.actions }}</th>
                </tr>
        </thead>
</template>

<style>

</style>
