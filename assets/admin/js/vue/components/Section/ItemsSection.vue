<script setup>
import {computed, inject, ref, watch } from 'vue'
import HeaderSection from './HeaderSection.vue';
import { useAjaxSwitchPosition, useMyfilter } from '../Base/BaseItems'

const props = defineProps(['header', 'items', 'norecord'])
const header = computed(() => {
   return props.header
})

let myitems = ref(props.items)
const indexchange = ref(-1)
const directionchange = ref('up')
const index1 = ref(1)
const index2 = ref(1)
const URI = `/fr/admin/ajax/switch-position/section`

const myselect = inject('myselect')

const getPostHref = (post, item) => {
  return "/" + item.locale + "/admin/menu/" + item.menu_slug + '/contenu/' + post.id + '/' + post.name
}

const getSectionHref = (item, config) => {
    if(0 == config){
        return "/" + item.locale + "/admin/section/edit/" + item.id + "/0"
    }
  return "/" + item.locale + "/admin/section/edit/" + item.id
}

const getAddSectionHref = (item) => {
  return "/" + item.locale + "/admin/menu/" + item.menu_slug + "/nouvelle-section/nouveau-contenu"
}


const getCopySectionHref = (item) => {
  return "/" + item.locale + "/admin/section/copy/" + item.id
}

const changeIndex = (direction, index) => {
    if( index != indexchange.value){
        indexchange.value = index
    }else{
        indexchange.value = index + 1
    }
    directionchange.value = direction
    myitems = getUpOrDown(direction, index)
}

watch(indexchange, (newIndex, oldValue) =>{
    myitems = getUpOrDown(directionchange.value, newIndex)
    }
)

// si on remonte un item d'un cran (et du coup on baisse d'un niveau celui qu'on remplace)
const getUpOrDown = (direction, index) => {
     if(index > -1){
        if('up' == direction){
            if(index == myitems.length){
                index1.value = index - 1
                index2.value = index - 2
            }else{
                index1.value = index
                index2.value = index - 1
            }
        }
        if('down' == direction){
            index1.value = index + 1
            index2.value = index
        }

        const up = myitems[index1.value]
        const down = myitems[index2.value]

        // mise à jour des position en base de donnéd
        useAjaxSwitchPosition(URI, down, up)
        const position2  = up['position']
        const position1  = down['position']

        // inverser position
        up['position'] =  position1
        down['position'] =  position2

        const arraydeb = myitems.slice(0, index1.value-1) // tableau 0 - "index-1"
        const arrayfin = myitems.slice(index1.value+1) // tableau index+1 - "fin"
        if('down' == direction){
            const arraydeb = myitems.slice(0, index2.value-1) // tableau 0 - "index-1"
            const arrayfin = myitems.slice(index2.value+1) // tableau index+1 - "fin"
        }

        arrayfin.unshift(down)
        arrayfin.unshift(up)
        myitems = arraydeb.concat(arrayfin)
    }else{
        myitems = props.items
    }
    return myitems
}
</script>

<template>
<table class="table mt-4" >
    <HeaderSection :header="header" ></HeaderSection>
    <tbody>
        <template v-for="(item, index) in getUpOrDown(directionchange, indexchange)">
        <tr v-if="useMyfilter(myselect,item.menu)" class="align-middle">
            <td class="col-1">
                <div class="form-check form-switch form-switch-sm my-0">
                    <input type="checkbox" class="section-active form-check-input" :id="item.id" :checked="item.active ? true : false">
                </div>
            </td>
            <td class="col-1">{{ item.locale }}</td>
            <td class="col-2">{{ item.sheet }}</td>
            <td class="col-2">{{ item.menu }}</td>
            <td class="col-1">
                <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary " @click="changeIndex('up', index)" >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm8.5 9.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                </svg> &nbsp;
                <span class="visually-hidden">Button</span>
                </button>
                <button type="button" class="btn btn-outline-secondary " @click="changeIndex('down', index)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                </svg>
                </button>
                </div> 
                {{ item.position }} 
            </td>
            <td class="col-1">{{ item.template }}</td>
            <td>
                <li v-for="(post) in item.posts"class="list-unstyled">
                    <a :class="[post.isActive ? 'hms-color1 h5' : 'hms-color1 h5 fst-italic text-decoration-line-through text-black-50']   " :href="getPostHref(post, item)">{{ post.name }}</a>
                </li>
            </td>
            <td class="col-2">
                <a class="ms-1" :id="item.code" :href="getSectionHref(item)"><i class="hms-color1 btn btn-outline-success fa fa-cogs"></i></a>
                <a class="ms-1" :id="item.code" :href="getSectionHref(item, 0)"><i class="hms-color1 btn btn-outline-success fa fa-edit"></i></a>
                <a class="ms-1" :id="item.name" :href="getAddSectionHref(item)" ><i class="hms-color1 btn btn-outline-success fa fa-plus-circle"></i></a>
                <a class="ms-1" :id="item.name" :href="getCopySectionHref(item)" ><i class="hms-color1 btn btn-outline-success fa fa-copy"></i></a>
            </td>
        </tr>
        <tr v-if="props.items.length < 1 " class="align-middle">
            <td colspan="5">{{props.norecord.norecord}}</td>
        </tr>
        </template>
    </tbody>
</table>
</template>

<style>

</style>
