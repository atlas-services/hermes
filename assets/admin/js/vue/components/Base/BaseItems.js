import {ref } from 'vue'

const index1 = ref(1)
const index2 = ref(1)

// on met à jour les positions des 2 items en base
export function useAjaxSwitchPosition(uri, item1, item2){
    const requestOptions = {
    method: "POST",
    headers: { 
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'Authorization': 'Bearer my-token',
    },
    body: JSON.stringify({'id1': item1['id'],  'id2': item2['id'] })
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


// si on remonte un item d'un cran (et du coup on baisse d'un niveau celui qu'on remplace)

export function useGetUpOrDown(myitems, URI, direction, index){
  if(index > -1){
      if('up' == direction){
          index1.value = index
          index2.value = index - 1
      }
      if('down' == direction){
          index1.value = index + 1
          index2.value = index
      }

      const up = myitems.value[index1.value]
      const down = myitems[index2.value]

      // mise à jour des position en base de donnéd
      useAjaxSwitchPosition(URI, down, up)
      const position2  = up['position']
      const position1  = down['position']


      // inverser position
      up['position'] =  position1
      down['position'] =  position2
      const arraydeb = myitems.value.slice(0, index1.value-1) // tableau 0 - "index-1"
      const arrayfin = myitems.value.slice(index1.value+1) // tableau index+1 - "fin"
      if('down' == direction){
          const arraydeb = myitems.slice(0, index2.value-1) // tableau 0 - "index-1"
          const arrayfin = myitems.slice(index2.value+1) // tableau index+1 - "fin"
      }

      arrayfin.unshift(down)
      arrayfin.unshift(up)
      myitems = arraydeb.concat(arrayfin)
  }else{
      myitems = myitems
  }

  return myitems
}
