

<div
  v-for="(item, index) in data.items"
  class="item mb-2 grid items-center"
  :class="data.items.length > 1 ? 'grid-cols-8': 'grid-cols-7'"
  :key="index">

  {{--              <input--}}
  {{--                type="text"--}}
  {{--                v-model="item.description"--}}
  {{--                :name="`items[${index}][description]`"--}}
  {{--                placeholder="Description"--}}
  {{--                class="w-full border-none rounded-s-md shadow-sm col-span-5"--}}
  {{--                required />--}}

  <NewInput
    v-model="item.description"
    class="w-full border-none rounded-s-md shadow-sm col-span-5" />

  <NewInput
    v-model="item.quantity"
    step="1"
    type="number"/>

  {{--              <input--}}
  {{--                type="number"--}}
  {{--                v-model="item.quantity"--}}
  {{--                :name="`items[${index}][quantity]`"--}}
  {{--                placeholder="Quantity"--}}
  {{--                class="border-none shadow-sm"--}}
  {{--                required />--}}

  {{--              <input--}}
  {{--                type="number"--}}
  {{--                v-model="item.unit_price"--}}
  {{--                :name="`items[${index}][unit_price]`"--}}
  {{--                placeholder="Unit Price"--}}
  {{--                class="border-none rounded-e-md shadow-sm"--}}
  {{--                required />--}}
  {{--              <CustomInput--}}
  {{--                v-model="item.unit_price"--}}
  {{--                placeholder="Enter unit price"--}}
  {{--                class="h-10"--}}
  {{--                type="number"  />--}}

  <NewInput
    v-model="item.unit_price"
    type="number"/>

  <article
    v-if="data.items.length > 1">
    <button
      type="button"
      @click="data.items.splice(index, 1)"
      class="ml-2 size-8 bg-red-500 text-white rounded-md flex items-center justify-center">
      <x-tabler-x class="size-5" />
    </button>
  </article>

</div>
