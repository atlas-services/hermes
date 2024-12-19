<script setup>
import {computed, inject, ref, watch } from 'vue'
import HeaderMenu from './HeaderMenu.vue';
import ButtonsUpDown from '../Base/ButtonsUpDown.vue';
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
                <div class="btn-group">
                    <ButtonsUpDown :class="{'disabled': index == 0 }"  :direction="'up'"   @click="changeIndex('up', index)"></ButtonsUpDown>
                    <ButtonsUpDown :class="{'disabled': index == last }"  :direction="'down'"    @click="changeIndex('down', index)"></ButtonsUpDown>
                </div>
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
