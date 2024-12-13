<!--
Ce composant récupère l'entête et les lignes des Sections (Pqages).
-->

<script setup>
import { computed, provide, reactive } from 'vue'
import FiltreSection from './FiltreSection.vue';
import ItemsSection from './ItemsSection.vue';
import SelectSection from './SelectSection.vue';
import ButtonsSection from './ButtonsSection.vue';

provide('myselect', false)

const datas = reactive(document.querySelector('#vue_items_section'))

const filtre = computed(() => {
   return JSON.parse(datas.dataset.filtre)
})

const items = computed(() => {
   return JSON.parse(datas.value)
})

const header = computed(() => {
   return JSON.parse(datas.dataset.global)
})

const norecord = computed(() => {
   return JSON.parse(datas.dataset.norecord)
})

const buttons = computed(() => {
   return JSON.parse(datas.dataset.buttons)
})

</script>

<template>
   <SelectSection>
      <FiltreSection :items="filtre" ></FiltreSection>
      <ItemsSection :header="header" :items="items" :norecord="norecord"></ItemsSection>
    </SelectSection>
    <ButtonsSection :buttons="buttons" ></ButtonsSection>

</template>

<style>

</style>
