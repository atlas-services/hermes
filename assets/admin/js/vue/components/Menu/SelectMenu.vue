<script setup>
import {provide, ref } from 'vue'

const myselect= ref('all')
provide('myselect', myselect)

</script>

<template>
    <slot></slot>
</template>

<style>

</style>
