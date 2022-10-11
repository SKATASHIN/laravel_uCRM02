<script setup> 
    import Label from '@/components/inputLabel.vue' 
    import Input from '@/components/TextInput.vue' 
    import GuestLayout from '@/Layouts/GuestLayout.vue' 

    const emitTest = e => console.log(e)
  </script> 

  <template> 

    <GuestLayout> 
    <Label>タイトル</Label> 
    <Input 
      modelValue="初期値です"
      @update:modelValue="emitTest"
      >
    </Input> 
    </GuestLayout> 

  </template>