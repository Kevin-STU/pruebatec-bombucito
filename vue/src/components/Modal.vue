<template>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h2 class="text-xl font-bold mb-4">{{ titulo }}</h2>
        <slot></slot>
        <div class="flex justify-end mt-4">
          <button @click="$emit('close')" class="bg-gray-500 text-white px-3 py-1 rounded mr-2">Cancelar</button>
          <button @click="$emit('save')" class="bg-blue-500 text-white px-3 py-1 rounded">Guardar</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: ['titulo']
  };
  </script>
  