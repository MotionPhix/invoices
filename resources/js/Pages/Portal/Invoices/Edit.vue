
<script setup lang="ts">
import { ref, computed } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  NumberField,
  NumberFieldContent,
  NumberFieldDecrement,
  NumberFieldIncrement,
  NumberFieldInput,
} from '@/components/ui/number-field'
import { Select, SelectItem, SelectTrigger, SelectValue, SelectContent } from '@/components/ui/select'

const props = defineProps<{
  invoice: any,
  products: Array<{ id: number, name: string, price: number }>,
  clients: Array<{ id: number, name: string }>,
}>()


const form = useForm({
  client_id: String(props.invoice.client_id),
  items: props.invoice.items.map((item: any) => ({
    product_id: String(item.product_id),
    quantity: item.quantity,
    price: item.price,
  })),
  notes: props.invoice.notes || '',
})

function addProductRow() {
  form.items.push({ product_id: '', quantity: 1, price: 0 })
}

function removeProductRow(index: number) {
  if (form.items.length > 1) form.items.splice(index, 1)
}

        <Button type="button" @click="addProductRow">Add Product/Service</Button>
      </div>
      <div>
        <label class="block mb-1 font-medium">Notes</label>
        <Input v-model="form.notes" placeholder="Additional notes" />
      </div>
      <Button type="submit">Update Invoice</Button>
    </form>
  </div>
</template>
