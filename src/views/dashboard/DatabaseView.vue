<template>
  <div>
    <v-row>
      <!-- Data table start -->
      <v-col cols="12" lg="8">
        <div class="add mb-4">
          <div class="add-btn">
            <el-dropdown>
              <el-button
                style="background-color: #4caf50; color: white; outline: none; box-shadow: none"
              >
                <i class="fas fa-plus mr-3"></i>
                Add a data
              </el-button>
              <template #dropdown>
                <el-dropdown-menu>
                  <RouterLink to="/addstudent">
                    <el-dropdown-item>Student</el-dropdown-item></RouterLink
                  >

                  <el-dropdown-item>Teacher</el-dropdown-item>
                  <el-dropdown-item>Staff</el-dropdown-item>
                </el-dropdown-menu>
              </template>
            </el-dropdown>
          </div>
        </div>
        <v-card>
          <v-card-text>
            <div class="header flex justify-between end">
              <div class="sort">
                <el-dropdown>
                  <el-button>
                    Sort
                    <i class="fas fa-arrow-down ml-1"></i>
                  </el-button>
                  <template #dropdown>
                    <el-dropdown-menu>
                      <el-dropdown-item>Date</el-dropdown-item>
                      <el-dropdown-item>Period</el-dropdown-item>
                    </el-dropdown-menu>
                  </template>
                </el-dropdown>
              </div>

              <div class="filter">
                <el-dropdown>
                  <el-button>
                    Filter
                    <i class="fas fa-filter ml-1"></i>
                  </el-button>
                  <template #dropdown>
                    <el-dropdown-menu>
                      <el-dropdown-item>Date</el-dropdown-item>
                      <el-dropdown-item>Period</el-dropdown-item>
                    </el-dropdown-menu>
                  </template>
                </el-dropdown>
              </div>
            </div>

            <div class="database-menu">
              <div
                class="menu-item"
                v-for="section in sections"
                :key="section"
                :class="{ active: activeSection === section }"
                @click="setActiveSection(section)"
              >
                {{ section }}
              </div>
            </div>

            <div class="section-content">
              <StudentsTable v-if="activeSection === 'Students'" />
              <TeachersTable v-else-if="activeSection === 'Teachers'" />
              <EmployeesTable v-else-if="activeSection === 'Employees'" />
            </div>
            <div class="data"></div>
          </v-card-text>
        </v-card>
      </v-col>
      <!-- Data table end -->

      <!-- Detailed data start -->
      <!-- Detailed data end -->
    </v-row>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import StudentsTable from '@/components/dataTables/StudentsTable.vue'
import TeachersTable from '@/components/dataTables/TeachersTable.vue'
import EmployeesTable from '@/components/dataTables/EmployeesTable.vue'

export default defineComponent({
  components: {
    StudentsTable,
    TeachersTable,
    EmployeesTable
  },
  setup() {
    const sections = ['Students', 'Teachers', 'Employees']
    const activeSection = ref(sections[0])

    const setActiveSection = (section: string) => {
      activeSection.value = section
    }

    return {
      sections,
      activeSection,
      setActiveSection
    }
  }
})
</script>

<style scoped>
.database-menu {
  display: flex;
  overflow-x: auto;
  border-bottom: 1px solid #ccc;
}
.menu-item {
  padding: 10px 20px;
  cursor: pointer;
  white-space: nowrap;
}
.menu-item.active {
  font-weight: bold;
  border-bottom: 2px solid #4caf50;
}
.ml-1 {
  margin-left: 4px;
}
</style>
