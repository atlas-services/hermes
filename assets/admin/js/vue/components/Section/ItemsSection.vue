<script setup>
import {computed, inject, ref, watch } from 'vue'
import HeaderSection from './HeaderSection.vue';
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

        // mise à jour des position en base de donnéd
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
                    <div class="btn-group">
                        <ButtonsUpDown :class="{'disabled': index == 0 }"  :direction="'up'"   @click="changeIndex('up', index)"></ButtonsUpDown>
                        <ButtonsUpDown :class="{'disabled': index == last }"  :direction="'down'"    @click="changeIndex('down', index)"></ButtonsUpDown>
                    </div>
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
