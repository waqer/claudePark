import { createRouter, createWebHistory } from 'vue-router'
import ZoneList   from '@/views/ZoneList.vue'
import ZoneDetail from '@/views/ZoneDetail.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'zone-list',
      component: ZoneList,
    },
    {
      path: '/zones/:id',
      name: 'zone-detail',
      component: ZoneDetail,
      props: route => ({ id: Number(route.params.id) }),
    },
    {
      // Catch-all → redirect home
      path: '/:pathMatch(.*)*',
      redirect: '/',
    },
  ],
})

export default router
