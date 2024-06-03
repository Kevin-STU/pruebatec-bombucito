<template>
  <body class="py-12">
    <div class="container mx-auto max-w-4xl">
      <button @click="abrirModal('Nuevo empleado')" class="bg-blue-500 text-white px-3 py-0.5 rounded mt-14">Nuevo empleado</button>
      <div class="bg-white shadow-md rounded-lg overflow-hidden mt-3">
        <table class="min-w-full bg-white">
          <thead>
            <tr>
              <th class="py-3 px-3 bg-blue-500 text-white text-left">
                <div class="flex items-center ml-3">
                  <input type="checkbox" class="mr-6">
                  Empleados
                </div>
              </th>
              <th class="py-3 px-3 bg-blue-500 text-white text-left">Cargo</th>
              <th class="py-3 px-3 bg-blue-500 text-white text-left">Ubicación</th>
              <th class="py-3 px-3 bg-blue-500 text-white text-left">Tipografía favorita</th>
              <th class="py-3 px-3 bg-blue-500 text-white text-left">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="empleado in empleados" :key="empleado.id" class="border-b">
              <td class="py-4 px-3">
                <div class="flex items-center ml-3">
                  <input type="checkbox" class="mr-6">
                  <img class="w-10 h-10 rounded-lg mr-3" :src="empleado.avatar ? `http://localhost:8000/storage/${empleado.avatar}` : 'https://via.placeholder.com/40'" alt="avatar">
                  <div>
                    <p class="font-bold">{{ empleado.nombre }}</p>
                    <p class="text-sm text-gray-600">{{ empleado.correo }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-3">
                <div class="flex items-center">
                  <span :style="getBadgeStyle(empleado.cargo)" class="py-1 px-3 rounded text-xs text-white">{{ empleado.cargo }}</span>
                </div>
              </td>
              <td class="py-4 px-3">
                <div class="flex items-center">
                  <i class="fa-solid fa-location-dot mr-1 text-gray-600"></i>
                  {{ empleado.ubicacion }}
                </div>
              </td>
              <td class="py-4 px-3">
                <div class="flex items-center">
                  {{ empleado.tipografia_favorita }}
                </div>
              </td>
              <td class="py-4 px-3">
                <div class="flex items-center">
                  <button @click="abrirModal('Editar empleado', empleado)" class="text-gray-600 hover:text-blue-700 mr-2"><i class="far fa-edit"></i></button>
                  <button @click="confirmarEliminarEmpleado(empleado.id)" class="text-gray-600 hover:text-red-700"><i class="far fa-trash-alt"></i></button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <Modal v-if="mostrarModal" :titulo="tituloModal" @close="cerrarModal" @save="guardarEmpleado">
      <div class="mb-4">
        <label class="block text-gray-700">Nombre</label>
        <input type="text" v-model="empleado.nombre" class="w-full px-3 py-2 border rounded">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Correo</label>
        <input type="email" v-model="empleado.correo" class="w-full px-3 py-2 border rounded">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Cargo</label>
        <select v-model="empleado.cargo" class="w-full px-3 py-2 border rounded">
          <option>Designer</option>
          <option>Engineer</option>
          <option>Product Manager</option>
        </select>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Ubicación</label>
        <input type="text" v-model="empleado.ubicacion" class="w-full px-3 py-2 border rounded">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Tipografía favorita</label>
        <input type="text" v-model="empleado.tipografia_favorita" class="w-full px-3 py-2 border rounded">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Foto</label>
        <input type="file" @change="onFileChange" class="w-full px-3 py-2 border rounded">
      </div>
    </Modal>
  </body>
</template>

<script>
import axios from 'axios';
import Modal from './Modal.vue';

axios.defaults.baseURL = 'http://localhost:8000';

export default {
  components: {
    Modal
  },
  data() {
    return {
      empleados: [],
      mostrarModal: false,
      tituloModal: '',
      empleado: {
        nombre: '',
        correo: '',
        cargo: '',
        ubicacion: '',
        tipografia_favorita: '',
        avatar: ''
      },
      avatar: null
    };
  },
  methods: {
    fetchEmpleados() {
      axios.get('/api/empleados')
        .then(response => {
          this.empleados = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
    abrirModal(titulo, empleado = null) {
      this.tituloModal = titulo;
      if (empleado) {
        this.empleado = { ...empleado };
      } else {
        this.empleado = {
          nombre: '',
          correo: '',
          cargo: '',
          ubicacion: '',
          tipografia_favorita: '',
          avatar: ''
        };
      }
      this.mostrarModal = true;
    },
    cerrarModal() {
      this.mostrarModal = false;
    },
    onFileChange(event) {
      this.avatar = event.target.files[0];
    },
    guardarEmpleado() {
      const formData = new FormData();
      formData.append('nombre', this.empleado.nombre);
      formData.append('correo', this.empleado.correo);
      formData.append('cargo', this.empleado.cargo);
      formData.append('ubicacion', this.empleado.ubicacion);
      formData.append('tipografia_favorita', this.empleado.tipografia_favorita);
      if (this.avatar) {
        formData.append('avatar', this.avatar);
      }
      if (this.empleado.id) {
        formData.append('_method', 'PUT');
        axios.post(`/api/empleados/${this.empleado.id}`, formData)
          .then(() => {
            this.fetchEmpleados();
            this.cerrarModal();
          })
          .catch(error => {
            console.error(error);
          });
      } else {
        axios.post('/api/empleados', formData)
          .then(() => {
            this.fetchEmpleados();
            this.cerrarModal();
          })
          .catch(error => {
            console.error(error);
          });
      }
    },
    confirmarEliminarEmpleado(id) {
      if (confirm('¿Estás seguro de que quieres eliminar este empleado?')) {
        this.eliminarEmpleado(id);
      }
    },
    eliminarEmpleado(id) {
      axios.delete(`/api/empleados/${id}`)
        .then(() => {
          this.fetchEmpleados();
        })
        .catch(error => {
          console.error(error);
        });
    },
    getBadgeStyle(cargo) {
      switch(cargo) {
        case 'Designer': return { backgroundColor: '#6b28d5', color: '#ffffff' };
        case 'Engineer': return { backgroundColor: '#28a0d5', color: '#ffffff' };
        case 'Product Manager': return { backgroundColor: '#3fc783', color: '#ffffff' };
        default: return { backgroundColor: '#808080', color: '#ffffff' };
      }
    }
  },
  mounted() {
    this.fetchEmpleados();
  }
};
</script>

<style>
input[type="checkbox"] {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  outline: none;
  width: 1.5em;
  height: 1.5em;
  border: 1px solid black;
  border-radius: 0.5em; /* More rounded corners */
  background-color: white;
  cursor: pointer;
  transition: background-color 0.3s, border-color 0.3s;
}

input[type="checkbox"]:checked {
  background-color: #3b82f6;
  border-color: black;
}

input[type="checkbox"]:checked::before {
  content: '';
  display: block;
  text-align: center;
  color: white;
  font-weight: bold;
}
</style>
