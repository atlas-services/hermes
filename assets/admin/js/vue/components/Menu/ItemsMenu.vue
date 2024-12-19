<script setup>
import {computed, inject, ref, watch } from 'vue'
import HeaderMenu from './HeaderMenu.vue';
import { useAjaxSwitchPosition, useMyfilter, useSwitchIndex } from '../Base/BaseItems'


const props = defineProps(['header', 'items', 'norecord'])
const header = computed(() => {
   return props.header
})
let myitems = ref(props.items)
const indexchange = ref(-1)
const directionchange = ref('up')
const index1 = ref(1)
const index2 = ref(1)
const URI = `/fr/admin/ajax/switch-position/menu`

const myselect = inject('myselect')

const getMenuHref = (item) => {
    return "/" + item.locale + "/admin/page/" + item.sheet_slug + "/edit/menu/" + item.referenceName + "/locale/" + item.locale
}

const getAddMenuHref = (item) => {
  return "/" + item.locale + "/admin/menu/" + item.slug + "/nouvelle-section/nouveau-contenu"
}
const changeIndex = (direction, index) => {
    if( index != indexchange.value){
        indexchange.value = index
    }else{
        indexchange.value = index + 1
    }
    directionchange.value = direction
   
    myitems = switchPosition(direction, index)
}

watch(indexchange, (newIndex, oldValue) =>{
    myitems = switchPosition(directionchange.value, newIndex)
    }
)


// si on remonte un item d'un cran (et du coup on baisse d'un niveau celui qu'on remplace)
const switchPosition = (direction, index) => {
    if(index > -1){
        const indexes = useSwitchIndex(direction, index,myitems.length)
        index1.value = indexes.index1
        index2.value = indexes.index2 
        // inverser position
        const up = myitems[index1.value]
        const down = myitems[index2.value]
        const position2  = up['position']
        const position1  = down['position']
        up['position'] =  position1
        down['position'] =  position2

        myitems.splice(index1.value, 1, myitems.splice(index2.value, 1, myitems[index1.value])[0]);

    }else{
        myitems = props.items
    }
    return myitems
}

// si on remonte un item d'un cran (et du coup on baisse d'un niveau celui qu'on remplace)
const getUpOrDown = (direction, index) => {
    if(index > -1){
        const indexes = useSwitchIndex(direction, index,myitems.length)
        index1.value = indexes.index1
        index2.value = indexes.index2

        // mise à jour des inversions de position en base de donnéd
        useAjaxSwitchPosition(URI, myitems[index1.value]['id'], myitems[index2.value]['id'])

        // inverser positions dans tableau
        const up = myitems[index1.value]
        const down = myitems[index2.value]
        const position2  = up['position']
        const position1  = down['position']
        up['position'] =  position1
        down['position'] =  position2

        myitems.splice(index1.value, 1, myitems.splice(index2.value, 1, myitems[index1.value])[0]);

    }else{
        myitems = props.items
    }
    return myitems
}
</script>

<template>
<table class="table mt-4" >
<HeaderMenu :header="header" ></HeaderMenu>
<tbody>
    <template v-for="(item, index) in getUpOrDown(directionchange, indexchange)">
    <tr v-if="useMyfilter(myselect,item.sheet)" class="align-middle">
        <td class="col-2">
            <div class="form-check form-switch form-switch-sm my-0">
                <input type="checkbox" class="menu-active form-check-input" :id="item.id" :checked="item.active ? true : false">
                <label class="custom-control-label" :for="item.id">{{ item.sheet }}</label>
            </div>
        </td>
        <td class="col-1">{{ item.locale }}</td>
        <td class="col-2">{{ item.sheet }}</td>
        <td class="col-2">{{ item.name }}</td>
        <td>
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
        <td>{{ item.fileName }}</td>
        <td class="col-1">
            <a :id="item.code" :href="getMenuHref(item)"><i class="hms-color1 btn btn-outline-success fa fa-edit"></i></a>
            <a :id="item.name" :href="getAddMenuHref(item)" class="ps-1"><i class="hms-color1 btn btn-outline-success fa fa-plus-circle"></i></a>
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
