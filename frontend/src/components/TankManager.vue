<template>
  <v-container>
    <!-- Форма заливки -->
    <v-card class="pa-4 mb-6">
      <h2>Залить молоко</h2>
      <v-form ref="formRef" v-model="valid" @submit.prevent="submitForm">
        <v-text-field
          v-model="form.name"
          label="Кто залил"
          :rules="[v => !!v || 'Введите имя']"
          required
          outlined
          rounded
          class="custom-input"
        />
        <v-text-field
          v-model.number="form.volume"
          label="Количество молока (л)"
          type="number"
          :rules="[
            v => !!v || 'Введите объём',
            v => v > 0 || 'Объём должен быть больше 0'
          ]"
          required
          outlined
          rounded
          class="custom-input"
        />
        <div class="buttons-wrapper">
          <v-btn
            class="blue-accent-btn"
            :disabled="!valid"
            type="submit"
            large
            rounded
          >
            Отправить
          </v-btn>

          <v-btn
            class="red-btn"
            @click="resetTanks"
            rounded
            large
          >
            Освободить цистерны
          </v-btn>
        </div>
      </v-form>
    </v-card>

    <!-- Текущие объёмы -->
    <v-card class="pa-4 mb-6">
      <h2>Состояние цистерн</h2>
      <v-table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Текущий объём</th>
            <th>Вместимость</th>
            <th>Заполнено (%)</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="tank in tanks" :key="tank.id">
            <td>{{ tank.id }}</td>
            <td>{{ tank.current_volume }} л</td>
            <td>{{ tank.capacity }} л</td>
            <td>{{ ((tank.current_volume / tank.capacity) * 100).toFixed(1) }}%</td>
          </tr>
        </tbody>
      </v-table>
    </v-card>

    <!-- История -->
    <v-card class="pa-4">
      <h2>История заливок</h2>
      <v-table>
        <thead>
          <tr>
            <th>Имя</th>
            <th>Объём (л)</th>
            <th>Цистерна</th>
            <th>Дата</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in fills" :key="index">
            <td>{{ item.user_name }}</td>
            <td>{{ item.volume }}</td>
            <td>{{ item.tank_id }}</td>
            <td>{{ formatDate(item.created_at) }}</td>
          </tr>
        </tbody>
      </v-table>
    </v-card>
  </v-container>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'

const valid = ref(false)
const formRef = ref(null)

const form = reactive({
  name: '',
  volume: null,
})

const tanks = ref([])
const fills = ref([])

function formatDate(ts) {
  const date = new Date(ts * 1000)
  return date.toLocaleString()
}

async function loadData() {
  try {
    const { data } = await axios.get('/milk/stat');
    if (data.success === false) {
      console.error('Backend error:', data.message);
      alert('Ошибка на сервере: ' + data.message);
      return;
    }

    // Сортировка цистерн по ID (вверх по ID — 1, 2, 3, ...)
    tanks.value = data.tanks.sort((a, b) => a.id - b.id);

    // История заливок уже отсортирована на сервере (по убыванию created_at)
    fills.value = data.fills;
  } catch (err) {
    console.error(err);
    alert('Ошибка загрузки данных');
  }
}

async function submitForm() {
  if (!formRef.value.validate()) return;

  console.log('Отправляем:', form.name, form.volume); // добавь эту строку

  try {
    const { data } = await axios.post('/milk/fill', {
      name: form.name,
      volume: form.volume,
    });

    if (data.success) {
      form.name = '';
      form.volume = null;
      formRef.value.resetValidation();
      await loadData();
    } else {
      alert(data.message || 'Ошибка при заливке');
    }
  } catch (e) {
    alert('Ошибка при отправке данных');
  }
}

async function resetTanks() {
  if (!confirm("Вы уверены, что хотите освободить все цистерны?")) return;

  try {
    const { data } = await axios.post('/milk/reset');
    if (data.success) {
      await loadData();
    } else {
      alert(data.message || 'Ошибка сброса');
    }
  } catch (err) {
    alert('Ошибка при сбросе цистерн');
  }
}

onMounted(loadData)
</script>

<style scoped>
h2 {
  margin-bottom: 16px;
}

.blue-accent-btn {
  background-color: #2979FF !important;
  color: white !important;
  padding: 12px 32px;
  font-size: 18px;
  min-width: 160px;
  height: 48px;
  border-radius: 12px;
  transition: box-shadow 0.3s ease;
  box-shadow: 0 2px 4px rgba(41, 121, 255, 0.5);
  text-align: center;
  display: flex !important;
  align-items: center;
  justify-content: center;
}

.blue-accent-btn:hover:not(:disabled) {
  box-shadow: 0 8px 20px rgba(41, 121, 255, 0.7);
}

.custom-input input {
  background-color: #F5F5F5 !important;
  border-radius: 10px !important;
}

.red-btn {
  background-color: #f31f1f !important;
  color: rgb(255, 255, 255) !important;
  padding: 12px 32px;
  font-size: 18px;
  min-width: 160px;
  height: 48px;
  border-radius: 12px;
  transition: box-shadow 0.3s ease;
  box-shadow: 0 2px 4px rgba(212, 22, 15, 0.76);
  text-align: center;
  display: flex !important;
  align-items: center;
  justify-content: center;
}

.red-btn:hover:not(:disabled) {
  box-shadow: 0 8px 20px rgba(212, 22, 15, 0.9);
}

.buttons-wrapper {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
}
</style>