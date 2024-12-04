<!--
Cet exemple récupère les derniers api_hermes_templates de Vue Core à partir de l'API de GitHub et les affiche sous forme de liste.
Vous pouvez basculer entre les deux id_templates principales.
-->

<script setup>
import { ref, watchEffect, onMounted } from 'vue'

const API_URL = `https://api.hermes-cms.org/api/templates/`
const id_templates =  range(1, 26)
const id_hermes = ref(id_templates[0])
const api_hermes_templates = ref({})

function range(start, end) {
    if(start === end) return [start];
    return [start, ...range(start + 1, end)];
} 

watchEffect(async () => {
  // cet effet va être exécuté directement puis
  // de nouveau chaque fois que id_hermes.value change
  api_hermes_templates.value  =  await (await fetchUri(id_hermes.value)).json()
})

function fetchUri(id){
  const url = `${API_URL}`+ id
  return fetch(url)
}

</script>

<template>
  <h1>Templates Hermes</h1>
  <template v-for="h_template in id_templates">
    <input type="radio"  :id="h_template" :value="h_template" :name="h_template" v-model="id_hermes">
    <label class="mx-3 my-2" :for="h_template">{{ h_template }}</label>
  </template>
  <hr class="border-2 bg-info"   />
  <p>Type Template Hermes : "{{ api_hermes_templates.type }}"</p>
  <p>Nom Template Hermes : "{{ api_hermes_templates.name }}"</p>
  <hr class="border-2 bg-info"   />
  <p class="col-10 mx-auto" ><span v-html="api_hermes_templates.content"></span></p>
</template>

<style>
a {
  text-decoration: none;
  color: #42b883;
}
li {
  line-height: 1.5em;
  margin-bottom: 20px;
}
.author,
.date {
  font-weight: bold;
}
</style>
