import {ref } from 'vue'

const index1 = ref(1)
const index2 = ref(1)

// on met à jour les positions des 2 items en base
export function useAjaxSwitchPosition(uri, id1, id2){
    const requestOptions = {
    method: "POST",
    headers: { 
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'Authorization': 'Bearer my-token',
    },
    body: JSON.stringify({'id1': id1,  'id2': id2 })
  };
  fetch(uri, requestOptions)
    .then(response => response.json())
}

export function useMyfilter(selected, item) {
  if(item!= selected && 'all' != selected){
      return false
  }
  return true
}

export function useSwitchIndex(direction, index, myitemslength) {
    if('up' == direction){
        if(index == myitemslength){
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

    return { 'index1': index1.value, 'index2': index2.value }
  }


export function useSwitchList(myitems, index1, index2) {
    const arraydeb = myitems.slice(0, index1.value-1) // tableau 0 - "index-1"
    const arrayfin = myitems.slice(index1.value+1) // tableau index+1 - "fin"
    if('down' == direction){
        const arraydeb = myitems.slice(0, index2.value-1) // tableau 0 - "index-1"
        const arrayfin = myitems.slice(index2.value+1) // tableau index+1 - "fin"
    }

    arrayfin.unshift(down)
    arrayfin.unshift(up)

    return { 'arraydeb': arraydeb.value, 'arrayfin': arrayfin.value }
  }


// si on remonte un item d'un cran (et du coup on baisse d'un niveau celui qu'on remplace)

export function useGetUpOrDown(myitems, URI, direction, index){
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
      myitems = myitems
  }

  return myitems
}
