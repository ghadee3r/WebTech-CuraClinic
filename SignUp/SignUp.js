document.addEventListener('DOMContentLoaded', () => {
    function showForm() {
        const selectedRole = document.querySelector('input[name="role"]:checked').value;
        const patientForm = document.getElementById('patient-form');
        const doctorForm = document.getElementById('doctor-form');

        if (selectedRole === 'patient') {
            patientForm.classList.remove('hidden');
            doctorForm.classList.add('hidden');
        } else if (selectedRole === 'doctor') {
            doctorForm.classList.remove('hidden');
            patientForm.classList.add('hidden');
        }
    }

   
    const radioButtons = document.querySelectorAll('input[name="role"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', showForm);
    });
});
