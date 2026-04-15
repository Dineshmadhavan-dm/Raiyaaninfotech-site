  @php
      $modalConfigs = [
          ['id' => 'Qualification', 'label' => 'Qualification', 'name' => 'qua_name', 'route' => 'qualificationpost'],
          ['id' => 'Department', 'label' => 'Department', 'name' => 'dep_name', 'route' => 'departmentpost'],
          ['id' => 'Designation', 'label' => 'Designation', 'name' => 'des_name', 'route' => 'designationpost'],
          ['id' => 'Jobtype', 'label' => 'Jobtype', 'name' => 'jobtype_name', 'route' => 'jobtypepost'],
          [
              'id' => 'Relationship',
              'label' => 'Relationship',
              'name' => 'relationship_name',
              'route' => 'relationshippost',
          ],
          ['id' => 'Bloodgroup', 'label' => 'Bloodgroup', 'name' => 'bloodgroup_name', 'route' => 'bloodgrouppost'],
          ['id' => 'Location', 'label' => 'Location', 'name' => 'branch_name', 'route' => 'branchpost'],
      ];
  @endphp
  <x-layout>

      {{-- Reusable Modal Component --}}

      @foreach ($modalConfigs as $config)
          <div class="modal fade" id="add{{ $config['id'] }}Modal" tabindex="-1"
              aria-labelledby="add{{ $config['id'] }}ModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="add{{ $config['id'] }}ModalLabel">Add {{ $config['label'] }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="{{ route($config['route']) }}" method="POST"
                          id="{{ strtolower($config['id']) }}Form">
                          @csrf
                          <div class="modal-body">
                              <div class="mb-3">
                                  <label for="new{{ $config['id'] }}" class="form-label">{{ $config['label'] }}
                                      Name</label>
                                  <input type="text" class="form-control" id="new{{ $config['id'] }}"
                                      name="{{ $config['name'] }}" value="{{ old($config['name']) }}"
                                      placeholder="Enter {{ strtolower($config['label']) }}">
                                  <span id="{{ strtolower($config['id']) }}_error" class="text-danger"></span>
                              </div>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="button" class="btn btn-primary"
                                  onclick="saveData('{{ strtolower($config['id']) }}', '{{ $config['name'] }}')">Save</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      @endforeach

      {{-- Shared JavaScript --}}
      <script>
          async function saveData(id, fieldName) {
              const input = document.getElementById('new' + capitalize(id));
              const errorSpan = document.getElementById(id + '_error');
              const form = document.getElementById(id + 'Form');
              errorSpan.textContent = '';

              if (input.value.trim() === '') {
                  errorSpan.textContent = `Please enter a ${fieldName.replace('_', ' ')}.`;
                  input.focus();
                  return;
              }

              const formData = new FormData(form);


              try {
                  const response = await fetch(form.action, {
                      method: 'POST',
                      headers: {
                          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                              'content'),
                          'Accept': 'application/json',
                          'Content-Type': 'application/json',
                      },
                      body: JSON.stringify({
                          [fieldName]: input.value.trim()
                      })
                  });


                  if (response.ok) {
                      Toastify({
                          text: `Added successfully! ${fieldName.replace('_', ' ')}: ${input.value.trim()}`,
                          duration: 4000,
                          close: false,
                          gravity: "top",
                          position: "right",
                          backgroundColor: "#10B981", // Modern green
                          offset: {
                              y: 65

                          },
                          style: {
                              boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                              borderRadius: "8px",
                              fontFamily: "'Inter', sans-serif",
                              fontSize: "14px",
                              width: "250px"
                          },
                          stopOnFocus: true,
                      }).showToast();
                      // Close modal
                      // Close modal
                      const modalEl = document.getElementById('add' + capitalize(id) + 'Modal');
                      const modal = bootstrap.Modal.getInstance(modalEl);
                      modal.hide();

                      // Reload appropriate fieldset content (3 for qualification, else 5)
                      if (id === 'qualification') {
                          reloadFieldset(3);
                      } else {
                          reloadFieldset(5);
                      }

                  } else {
                      let data;
                      try {
                          data = await response.json();
                      } catch (e) {
                          throw new Error('Invalid JSON received');
                      }

                      if (data.errors && data.errors[fieldName]) {
                          errorSpan.textContent = data.errors[fieldName][0];
                      } else {
                          errorSpan.textContent = 'Something went wrong!';
                      }
                  }

              } catch (err) {
                  errorSpan.textContent = 'Network error! Please try again.';
                  console.error(err);
              }
          }

          function capitalize(str) {
              return str.charAt(0).toUpperCase() + str.slice(1);
          }

          // Reload fieldset function from first script
          function reloadFieldset(step) {
              const url = new URL(window.location.href);
              url.searchParams.set('step', step); // e.g. ?step=3 or ?step=5

              fetch(url.toString(), {
                      headers: {
                          'X-Requested-With': 'XMLHttpRequest'
                      }
                  })
                  .then(res => res.text())
                  .then(html => {
                      const parser = new DOMParser();
                      const doc = parser.parseFromString(html, 'text/html');
                      const newFieldset = doc.querySelector(`#fieldset-${step}`);
                      if (newFieldset) {
                          const currentFieldset = document.querySelector(`#fieldset-${step}`);
                          if (currentFieldset) {
                              currentFieldset.innerHTML = newFieldset.innerHTML;
                          }
                          document.querySelectorAll('fieldset').forEach(fs => {
                              fs.style.display = 'none';
                          });
                          document.querySelector(`#fieldset-${step}`).style.display = 'block';
                      }
                  })
                  .catch(err => console.error('Error reloading fieldset:', err));
          }
      </script>


      {{-- search functionality --}}
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">




      <!-- Toastify JS -->
      <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>



      @section('title', 'Employee Update')
      <div class="container-fluid py-4 px-4">
          <!-- Header & Breadcrumb -->
          <div class="d-flex justify-content-between align-items-center mb-4">
              <div>
                  <nav aria-label="breadcrumb">
                      <ol class="breadcrumb bg-transparent mb-0">
                          <li class="breadcrumb-item">
                              <a href="#" class="text-decoration-none text-muted">
                                  <i class="bi bi-people-fill me-2"></i>Employment
                              </a>
                          </li>
                          <li class="breadcrumb-item active text-primary" aria-current="page">Employee</li>
                      </ol>
                  </nav>
              </div>
              <a href="{{ route('emplist') }}" class="btn btn-outline-primary d-flex align-items-center">
                  <i class="bi bi-arrow-left-circle me-2"></i>Back
              </a>
          </div>

          <x-message />
          <div class="container-fluid">
              <!-- Multi-step form -->





              <form id="msform" method="POST" action="{{ route('empupdate', $employee->emp_id) }}"
                  class="animate__animated" enctype="multipart/form-data" novalidate>
                  @csrf
                  @method('PUT')
                  <input type="hidden" id="employee_id" name="employee_id" value="{{ $employee->emp_id }}">
                  <input type="hidden" id="step" name="step" value="{{ $step }}">

                  <!-- progressbar -->
                  <div class="dual-progress-container">
                      <!-- Vertical progress bar for desktop (730px+) -->
                      <ul class="vertical-progress">
                          @for ($i = 1; $i <= 6; $i++)
                              <li class="{{ $step >= $i ? ($step > $i ? 'completed' : 'active') : '' }}">
                                  <span class="step-label">
                                      <span class="fw-bold text-dark fs-6">Step {{ $i }}</span>
                                      @switch($i)
                                          @case(1)
                                              Personal Details
                                          @break

                                          @case(2)
                                              Family Details
                                          @break

                                          @case(3)
                                              Education Details
                                          @break

                                          @case(4)
                                              Past Employment
                                          @break

                                          @case(5)
                                              Current Employment
                                          @break

                                          @case(6)
                                              Professional References
                                          @break
                                      @endswitch
                                  </span>
                              </li>
                          @endfor
                      </ul>

                      <!-- Horizontal progress bar for mobile -->
                      <ul id="progressbar">
                          @for ($i = 1; $i <= 6; $i++)
                              <li class="{{ $step >= $i ? ($step > $i ? 'completed' : 'active') : '' }}">
                                  <span class="progress-label">
                                      <p class="fw-bold text-dark">Step {{ $i }}</p>
                                      @switch($i)
                                          @case(1)
                                              Personal Details
                                          @break

                                          @case(2)
                                              Family Details
                                          @break

                                          @case(3)
                                              Education Details
                                          @break

                                          @case(4)
                                              Past Employment
                                          @break

                                          @case(5)
                                              Current Employment
                                          @break

                                          @case(6)
                                              Professional References
                                          @break
                                      @endswitch
                                  </span>
                              </li>
                          @endfor
                      </ul>
                  </div>

                  <div class="form-container">
                      <!-- Fieldset 1: Personal Details -->
                      <fieldset style="{{ $step != 1 }}">
                          <div class="card">
                              <div class="card-header">
                                  <div class="row justify-content-between">
                                      <div class="col-auto align-content-center">

                                          <h4 class="mb-0">Personal Details</h4>


                                      </div>
                                      <div class="col-auto mt-3 mt-xxl-0  image-upload-container">
                                          @if ($employee->image)
                                              <div class="">
                                                  <img src="{{ $employee->image ? asset('employee_images/' . $employee->image) : asset('employee_images/1_emp.jpeg') }}"
                                                      alt="Employee Image" width="100" class="img-thumbnail">

                                              </div>
                                              {{-- <button type="button" class="mt-2 badge bg-danger  remove-image">
                                                X
                                            </button> --}}
                                          @endif
                                      </div>
                                  </div>
                              </div>
                              <div class="card-body mt-3">
                                  <div class="row ">
                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="fullname" class="form-label">Full Name <span
                                                      class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="fullname" name="fullname"
                                                  value="{{ old('fullname', $employee->fullname) }}" required>
                                          </div>
                                      </div>




















                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="personal_email" class="form-label">Email (Personal) <span
                                                      class="text-danger">*</span></label>
                                              <input type="email" class="form-control email-input"
                                                  id="personal_email" name="personal_email"
                                                  value="{{ old('personal_email', $employee->personal_email) }}"
                                                  required>
                                              <small class="text-danger error-message" style="display:none;">Invalid
                                                  email format</small>
                                          </div>



                                      </div>


                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="personal_mobile" class="form-label">Personal Mobile No
                                                  <span class="text-danger">*</span></label>
                                              <input type="tel" class="form-control" id="personal_mobile"
                                                  name="personal_mobile"
                                                  value="{{ old('personal_mobile', $employee->personal_mobile) }}"
                                                  required maxlength="13">
                                          </div>
                                      </div>



                                  </div>





                                  <div class="row">




                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label class="form-label">Gender <span
                                                      class="text-danger">*</span></label>
                                              <div class="d-flex gap-3">
                                                  <div class="form-check">
                                                      <input class="form-check-input" type="radio" name="gender"
                                                          id="male" value="male" required
                                                          {{ old('gender', $employee->gender ?? '') === 0 ? 'checked' : '' }}>

                                                      <label class="form-check-label" for="male">Male</label>
                                                  </div>
                                                  <div class="form-check">

                                                      <input class="form-check-input" type="radio" name="gender"
                                                          id="female" value="female" required
                                                          {{ old('gender', $employee->gender ?? '') === 1 ? 'checked' : '' }}>

                                                      <label class="form-check-label" for="female">Female</label>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>


                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="fathername" class="form-label">Father Name </label>
                                              <input type="text" class="form-control" id="fathername"
                                                  name="fathername"
                                                  value="{{ old('fathername', $employee->fathername) }}">
                                          </div>
                                      </div>



                                      <div class="col-xxl-4">
                                          <div class="form-group ">
                                              <label for="employee_image">Employee Image</label>
                                              <div class="">
                                                  <input type="file" class="form-control" id="employee_image"
                                                      name="employee_image" accept="image/jpeg,image/jpg,image/png">
                                                  <div id="image_error" class="invalid-feedback"></div>

                                              </div>
                                          </div>
                                          <input type="hidden" id="cropped_image_data" name="cropped_image_data"
                                              value="">
                                          <input type="hidden" id="remove_image" name="remove_image"
                                              value="0">
                                      </div>


                                  </div>



                                  <div class="row">

                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label class="form-label">Marital Status</label>
                                              <div class="d-flex gap-3">
                                                  <div class="form-check">
                                                      <input class="form-check-input" type="radio"
                                                          name="marital_status" id="married" value="married"
                                                          {{ old('marital_status', $employee->marital_status ?? '') == 1 ? 'checked' : '' }}>
                                                      <label class="form-check-label" for="married">Married</label>
                                                  </div>
                                                  <div class="form-check">
                                                      <input class="form-check-input" type="radio"
                                                          name="marital_status" id="single" value="single"
                                                          {{ old('marital_status', $employee->marital_status ?? '') == 0 ? 'checked' : '' }}>
                                                      <label class="form-check-label" for="single">Single</label>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>


                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="bloodgroup" class="form-label">Blood Group <span
                                                      class="text-danger">*</span></label>
                                              <div class="input-group">
                                                  <input type="text" class="form-control" id="bloodgroup"
                                                      name="bloodgroup_name"
                                                      value="{{ old('bloodgroup_name', $employee->bloodGroupid->bloodgroup_name ?? '') }}"
                                                      placeholder="Search bloodgroup ..."  required>
                                                  <input type="hidden" id="bloodgroup_id" name="bloodgroup">
                                              </div>
                                              <ul id="bloodgroup-list" class="list-group" style="display: none;">
                                              </ul>
                                          </div>
                                      </div>

                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="dob" class="form-label">Date Of Birth</label>
                                              <input type="date" class="form-control" id="dob"
                                                  name="dob" value="{{ old('dob', $employee->dob) }}">
                                          </div>
                                      </div>

                                  </div>


                                  <div class="row">

                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="pancard_no" class="form-label">PAN No </label>
                                              <input type="text" class="form-control" id="pancard_no"
                                                  name="pancard_no"
                                                  value="{{ old('pancard_no', $employee->pancard_no) }}"
                                                  title="Please enter valid PAN (e.g., ABCDE1234F)" maxlength="10">

                                          </div>
                                      </div>

                                      <div class="col-xxl-4">
                                          <div class="form-group ">
                                              <label for="aadhaar_no" class="form-label">Aadhaar No</label>
                                              <input type="text" class="form-control" id="aadhaar_no"
                                                  name="aadhaar_no"
                                                  value="{{ old('aadhaar_no', $employee->aadhaar_no) }}"
                                                  title="Enter valid 12-digit Aadhaar number (e.g., 2345 6789 0123 or 234567890123)"
                                                  maxlength="14">

                                          </div>
                                      </div>

                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="pincode" class="form-label">Pincode</label>
                                              <input type="number" class="form-control" id="pincode"
                                                  name="pincode" value="{{ old('pincode', $employee->pincode) }}"
                                                  min="100000" max="999999" maxlength="6"
                                                  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                  placeholder="Enter 6-digit pincode">
                                              <small class="text-muted">Enter 6-digit pincode to auto-fill
                                                  Country-state-city</small>
                                          </div>
                                      </div>

                                  </div>


                                  <div class="row">



                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="flatno" class="form-label">Flat No</label>
                                              <input type="text" class="form-control" id="flatno"
                                                  name="flatno" value="{{ old('flatno', $employee->flatno) }}">
                                          </div>
                                      </div>
                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="street" class="form-label">Street</label>
                                              <input type="text" class="form-control" id="street"
                                                  name="street" value="{{ old('street', $employee->street) }}">
                                          </div>
                                      </div>


                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="address" class="form-label">Address</label>
                                              <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $employee->address) }}</textarea>
                                          </div>
                                      </div>

                                  </div>


                                  <div class="row">

                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="country" class="form-label">Country</label>
                                              <input type="text" class="form-control" id="country"
                                                  name="country" value="{{ old('country', $employee->country) }}"
                                                  readonly>
                                          </div>
                                      </div>
                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="state" class="form-label">State</label>
                                              <input type="text" class="form-control" id="state"
                                                  name="state" value="{{ old('state', $employee->state) }}"
                                                  readonly>
                                          </div>
                                      </div>

                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="city" class="form-label">City</label>
                                              <input type="text" class="form-control" id="city"
                                                  name="city" value="{{ old('city', $employee->city) }}" readonly>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="row">





                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="address" class="form-label">Address</label>
                                              <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $employee->address) }}</textarea>
                                          </div>
                                      </div>

                                  </div>


                                  <style>
                                      /* Style for readonly fields */
                                      input[readonly] {
                                          background-color: #f8f9fa;
                                          cursor: not-allowed;
                                          border-color: #e9ecef;
                                      }

                                      /* Loading indicator for pincode lookup */
                                      #pincode-loading {
                                          display: none;
                                          position: absolute;
                                          right: 10px;
                                          top: 50%;
                                          transform: translateY(-50%);
                                      }
                                  </style>



                                  <h5 class="mb-3 mt-5">Emergency Contact Details</h5>

                                  <div class="row ">
                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="c_person_emergency" class="form-label">Contact Person Name
                                                  <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="c_person_emergency"
                                                  name="c_person_emergency"
                                                  value="{{ old('c_person_emergency', $employee->c_person_emergency) }}">
                                          </div>
                                      </div>

                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="relationship" class="form-label">Relation</label>
                                              <div class="input-group">
                                                  <input type="text" class="form-control" id="relationship"
                                                      name="relationship_name"
                                                      value="{{ old('relationship_name', $employee->relationShipid->relationship_name ?? '') }}"
                                                      placeholder="Search relation ...">
                                                  <input type="hidden" id="relationship_id" name="relationship"
                                                      value="{{ old('relationship', $employee->relationship_id ?? '') }}">

                                              </div>
                                              <ul id="relationship-list" class="list-group" style="display: none;">
                                              </ul>
                                          </div>
                                      </div>


                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="emergency_contact" class="form-label">Emergency Contact
                                                  <span class="text-danger">*</span></label>
                                              <input type="tel" class="form-control" id="emergency_contact"
                                                  name="emergency_contact"
                                                  value="{{ old('emergency_contact', $employee->emergency_contact) }}"
                                                  maxlength="13">
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="d-flex justify-content-end ">
                              <button type="button" name="next" class="btn btn-primary next action-button btn-sm"
                                  data-step="1">Next <i class="bi bi-arrow-right ms-2"></i></button>
                          </div>
                      </fieldset>

                      <!-- Fieldset 2: Family Details -->
                      <fieldset style="{{ $step != 2 ? 'display:none;' : '' }}">
                          <div class="card">
                              <div class="card-header">
                                  <h4 class="mb-0">Family Details</h4>
                              </div>
                              <div class="card-body">
                                  <div id="familyContainer">
                                      <div class="row justify-content-between">
                                          <div class="col-auto">
                                              <h5 class="mb-3">Family Details</h5>
                                          </div>
                                          <div class="col-auto">
                                              <button type="button" id="addFamily"
                                                  class="btn btn-outline-primary mb-3">
                                                  <i class="bi bi-plus-circle me-2"></i>Add
                                              </button>
                                          </div>
                                      </div>

                                      @if (count($families) > 0)
                                          @foreach ($families as $index => $family)
                                              <div class="family-entry ">



                                                  <div class="row ">
                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="fa_name_{{ $index }}"
                                                                  class="form-label">Name <span
                                                                      class="text-danger">*</span></label>
                                                              <input type="text" class="form-control"
                                                                  id="fa_name_{{ $index }}" name="fa_name[]"
                                                                  value="{{ $family->fa_name }}">
                                                          </div>
                                                      </div>








                                                      <div class="col-lg-4">
                                                          <div class="form-group">
                                                              <label for="fa_relation_{{ $index }}"
                                                                  class="form-label">Relation</label>
                                                              <div class="input-group">
                                                                  <input type="text" class="form-control"
                                                                      id="fa_relation_{{ $index }}"
                                                                      name="fa_relation_name[]"
                                                                      value="{{ $family->relationShipid->relationship_name ?? '' }}"
                                                                      placeholder="Search relation ...">
                                                                  <input type="hidden"
                                                                      id="fa_relation_id_{{ $index }}"
                                                                      name="fa_relation[]"
                                                                      value="{{ $family->fa_relation ?? '' }}">



                                                              </div>
                                                              <ul id="fa_relation_{{ $index }}_list"
                                                                  class="list-group"
                                                                  style="
    position: absolute;
    border: 2px solid #2b2929;
    max-height: 200px;
    overflow-y: auto;
    color: #ffff;
    width: 100%;
    z-index: 10;
    display: none;">
                                                              </ul>

                                                          </div>
                                                      </div>





                                                      <div class="col-lg-4">
                                                          <div class="form-group">
                                                              <label for="fa_occupation_{{ $index }}"
                                                                  class="form-label">Occupation <span
                                                                      class="text-danger">*</span></label>
                                                              <input type="text" class="form-control"
                                                                  id="fa_occupation_{{ $index }}"
                                                                  name="fa_occupation[]"
                                                                  value="{{ $family->fa_occupation }}">
                                                          </div>
                                                      </div>
                                                  </div>

                                                  @if ($index > 0)
                                                      <div class="text-end">
                                                          <button type="button"
                                                              class="btn btn-outline-danger btn-sm remove-family">
                                                              <i class="bi bi-trash me-1"></i>Remove
                                                          </button>
                                                      </div>
                                                  @endif
                                              </div>
                                          @endforeach
                                      @else
                                          <div class="family-entry">
                                              <div class="row">





                                                  <div class="col-lg-4">
                                                      <div class="form-group">
                                                          <label for="fa_name_0" class="form-label">Name </label>
                                                          <input type="text" class="form-control" id="fa_name_0"
                                                              name="fa_name[]">
                                                      </div>
                                                  </div>













                                                  <div class="col-lg-4">
                                                      <div class="form-group">
                                                          <label for="fa_relation_0"
                                                              class="form-label">Relation</label>
                                                          <div class="input-group">
                                                              <input type="text" class="form-control"
                                                                  id="fa_relation_0" name="fa_relation_name[]"
                                                                  placeholder="Search relation ...">
                                                              <input type="hidden" id="fa_relation_id_0"
                                                                  name="fa_relation[]">
                                                          </div>
                                                          <ul id="fa_relation_0_list" class="list-group"
                                                              style="display: none;"></ul>
                                                      </div>
                                                  </div>


                                                  <div class="col-lg-4">
                                                      <div class="form-group">
                                                          <label for="fa_occupation_0"
                                                              class="form-label">Occupation</label>
                                                          <input type="text" class="form-control"
                                                              id="fa_occupation_0" name="fa_occupation[]">
                                                      </div>
                                                  </div>

                                              </div>
                                          </div>
                                      @endif
                                  </div>
                              </div>
                          </div>

                          <div class="d-flex justify-content-between mt-3">
                              <button type="button" name="previous"
                                  class="btn btn-secondary btn-sm previous action-button"><i
                                      class="bi bi-arrow-left me-2"></i>Previous</button>
                              <button type="button" name="next" class="btn btn-primary btn-sm next action-button"
                                  data-step="2">Next <i class="bi bi-arrow-right ms-2"></i></button>
                          </div>
                      </fieldset>

                      <!-- Fieldset 3: Education Details -->
                      <fieldset style="{{ $step != 3 ? 'display:none;' : '' }}">
                          <div class="card">
                              <div class="card-header">
                                  <h4 class="mb-0">Education Details</h4>
                              </div>
                              <div class="card-body">
                                  <div id="educationContainer">
                                      <div class="text-end">
                                          <button type="button" id="addEducation"
                                              class="btn btn-outline-primary mb-3">
                                              <i class="bi bi-plus-circle me-2"></i>Add
                                          </button>
                                      </div>

                                      @if (count($educations) > 0)
                                          @foreach ($educations as $index => $education)
                                              <div class="education-entry ">


                                                  <div class="row ">


                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="qualification_{{ $index }}"
                                                                  class="form-label">Qualification
                                                                  <span class="text-danger">*</span>
                                                              </label>
                                                              <div class="input-group">
                                                                  <input type="text" class="form-control"
                                                                      id="qualification_{{ $index }}"
                                                                      name="qualification_name[]"
                                                                      value="{{ $education->Qualificationid->qua_name ?? '' }}"
                                                                      placeholder="Search qualification ..." required>
                                                                  <input type="hidden"
                                                                      id="qualification_id_{{ $index }}"
                                                                      name="qualification[]"
                                                                      value="{{ $education->qualification ?? '' }}">
                                                                  <button type="button"
                                                                      class="btn btn-outline-primary"
                                                                      data-bs-toggle="modal"
                                                                      data-bs-target="#addQualificationModal">
                                                                      Add
                                                                  </button>

                                                              </div>
                                                              <ul id="qualification_{{ $index }}_list"
                                                                  class="list-group"
                                                                  style="
    position: absolute;
    border: 2px solid #2b2929;
    max-height: 200px;
    overflow-y: auto;
    color: #ffff;
    width: 100%;
    z-index: 10;
    display: none;">
                                                              </ul>
                                                          </div>
                                                      </div>


                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="name_of_institution_{{ $index }}"
                                                                  class="form-label">Name Of Institution <span
                                                                      class="text-danger">*</span></label>
                                                              <input type="text" class="form-control"
                                                                  id="name_of_institution_{{ $index }}"
                                                                  name="name_of_institution[]"
                                                                  value="{{ $education->name_of_institution }}"
                                                                  placeholder="School/College/University" required>
                                                          </div>
                                                      </div>
                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="edu_location_{{ $index }}"
                                                                  class="form-label">Location <span
                                                                      class="text-danger">*</span></label>
                                                              <input type="text" class="form-control"
                                                                  id="edu_location_{{ $index }}"
                                                                  name="edu_location[]"
                                                                  value="{{ $education->edu_location }}"
                                                                  placeholder="Enter location" required>
                                                          </div>
                                                      </div>
                                                  </div>

                                                  <hr>
                                                  <h5 class="mb-3 mt-3">Period Of Study</h5>

                                                  <div class="row ">
                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="edu_from_date_{{ $index }}"
                                                                  class="form-label">From<span
                                                                      class="text-danger">*</span></label>
                                                              <input type="date" class="form-control"
                                                                  id="edu_from_date_{{ $index }}"
                                                                  name="edu_from_date[]"
                                                                  value="{{ $education->edu_from_date }}" required>
                                                          </div>
                                                      </div>
                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="edu_to_date_{{ $index }}"
                                                                  class="form-label">To<span
                                                    class="text-danger ">*</span></label>
                                                              <input type="date" class="form-control"
                                                                  id="edu_to_date_{{ $index }}"
                                                                  name="edu_to_date[]"
                                                                  value="{{ $education->edu_to_date }}" required>
                                                          </div>
                                                      </div>

                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="specialization_{{ $index }}"
                                                                  class="form-label">Specialization <span
                                                                      class="text-danger">*</span></label>
                                                              <input type="text" class="form-control"
                                                                  id="specialization_{{ $index }}"
                                                                  name="specialization[]"
                                                                  value="{{ $education->specialization }}" required>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="row ">

                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="percentage_grade_{{ $index }}"
                                                                  class="form-label">
                                                                  Percentage <span class="text-danger">*</span>
                                                              </label>
                                                              <div class="input-group">
                                                                  <input type="text"
                                                                      class="form-control percentage-input"
                                                                      id="percentage_grade_{{ $index }}"
                                                                      name="percentage_grade[]"
                                                                      value="{{ $education->percentage_grade }}"
                                                                      placeholder="Enter Percentage" required
                                                                      pattern="^(100(\.00?)?|[0-9]{1,2}(\.\d{1,2})?)$">
                                                                  <span class="input-group-text">%</span>
                                                              </div>
                                                          </div>
                                                      </div>

                                                  </div>
                                                  @if ($index > 0)
                                                      <div class="text-end">
                                                          <button type="button"
                                                              class="btn btn-outline-danger btn-sm remove-education">
                                                              <i class="bi bi-trash me-1"></i>Remove
                                                          </button>
                                                      </div>
                                                  @endif
                                              </div>
                                          @endforeach
                                      @else
                                          <div class="education-entry ">



                                              <div class="row ">
                                                  <div class="col-xxl-4">
                                                      <div class="form-group">
                                                          <label for="qualification_0"
                                                              class="form-label">Qualification
                                                              <span class="text-danger">*</span>
                                                          </label>
                                                          <div class="input-group">
                                                              <input type="text" class="form-control"
                                                                  id="qualification_0" name="qualification_name[]"
                                                                  placeholder="Search qualification ..." required>
                                                              <input type="hidden" id="qualification_id_0"
                                                                  name="qualification[]">

                                                              <button type="button" class="btn btn-outline-primary"
                                                                  data-bs-toggle="modal"
                                                                  data-bs-target="#addQualificationModal">
                                                                  Add
                                                              </button>

                                                          </div>
                                                          <ul id="qualification_0_list" class="list-group"
                                                              style="display: none;"></ul>
                                                      </div>
                                                  </div>


                                                  <div class="col-xxl-4">
                                                      <div class="form-group">
                                                          <label for="name_of_institution_0" class="form-label">Name
                                                              Of
                                                              Institution <span class="text-danger">*</span></label>
                                                          <input type="text" class="form-control"
                                                              id="name_of_institution_0" name="name_of_institution[]"
                                                              placeholder="School/College/University" required>
                                                      </div>
                                                  </div>
                                                  <div class="col-xxl-4">
                                                      <div class="form-group">
                                                          <label for="edu_location_0" class="form-label">Location
                                                              <span class="text-danger">*</span></label>
                                                          <input type="text" class="form-control"
                                                              id="edu_location_0" name="edu_location[]"
                                                              placeholder="Enter location" required>
                                                      </div>
                                                  </div>
                                              </div>

                                              <hr>
                                              <h5 class="mb-3 mt-3">Period Of Study</h5>

                                              <div class="row ">
                                                  <div class="col-xxl-4">
                                                      <div class="form-group">
                                                          <label for="edu_from_date_0" class="form-label">From<span
                                                                  class="text-danger">*</span></label>
                                                          <input type="date" class="form-control"
                                                              id="edu_from_date_0" name="edu_from_date[]" required>
                                                      </div>
                                                  </div>
                                                  <div class="col-xxl-4">
                                                      <div class="form-group">
                                                          <label for="edu_to_date_0" class="form-label">To</label>
                                                          <input type="date" class="form-control"
                                                              id="edu_to_date_0" name="edu_to_date[]" required>
                                                      </div>
                                                  </div>

                                                  <div class="col-xxl-4">
                                                      <div class="form-group">
                                                          <label for="specialization_0"
                                                              class="form-label">Specialization
                                                              <span class="text-danger">*</span></label>
                                                          <input type="text" class="form-control"
                                                              id="specialization_0" name="specialization[]" required>
                                                      </div>
                                                  </div>
                                              </div>

                                              <div class="row ">

                                                  <div class="col-xxl-4">
                                                      <div class="form-group">
                                                          <label for="percentage_grade_0"
                                                              class="form-label">Percentage
                                                              <span class="text-danger">*</span>
                                                          </label>
                                                          <div class="input-group">
                                                              <input type="text"
                                                                  class="form-control percentage-input"
                                                                  id="percentage_grade_0" name="percentage_grade[]"
                                                                  placeholder="Enter Percentage" required
                                                                  pattern="^(100(\.00?)?|[0-9]{1,2}(\.\d{1,2})?)$">
                                                              <span class="input-group-text">%</span>
                                                          </div>
                                                      </div>
                                                  </div>

                                              </div>
                                          </div>
                                      @endif
                                  </div>
                              </div>
                          </div>
                          <div class="d-flex justify-content-between mt-3">
                              <button type="button" name="previous"
                                  class="btn btn-secondary btn-sm previous action-button"><i
                                      class="bi bi-arrow-left me-2"></i>Previous</button>
                              <button type="button" name="next" class="btn btn-primary btn-sm next action-button"
                                  data-step="3">Next <i class="bi bi-arrow-right ms-2"></i></button>
                          </div>
                      </fieldset>

                      <!-- Fieldset 4: Past Employment -->
                      <fieldset style="{{ $step != 4 ? 'display:none;' : '' }}">
                          <div class="card">
                              <div class="card-header">
                                  <h4 class="mb-0">Past Employment Details (Last Three Organisations)</h4>
                              </div>
                              <div class="card-body">
                                  <div id="pastEmploymentContainer">
                                      <div class="text-end">
                                          <button type="button" id="addPastEmployment"
                                              class="btn btn-outline-primary mb-3">
                                              <i class="bi bi-plus-circle me-2"></i>Add
                                          </button>
                                      </div>

                                      @if (count($pastEmployments) > 0)
                                          @foreach ($pastEmployments as $index => $employment)
                                              <div class="past-employment-entry mb-4 border-bottom pb-4">
                                                  <div class="form-group mb-3">
                                                      <label for="employed_as_{{ $index }}"
                                                          class=" form-label">Employed As <span
                                                              class="text-danger">*</span></label>
                                                      <select class="form-select"
                                                          id="employed_as_{{ $index }}" name="employed_as[]"
                                                          required>
                                                          <option value="">Select Type</option>
                                                          <option value="0"
                                                              {{ $employment->employed_as == '0' ? 'selected' : '' }}>
                                                              New</option>
                                                          <option value="1"
                                                              {{ $employment->employed_as == '1' ? 'selected' : '' }}>
                                                              Experienced</option>
                                                      </select>
                                                  </div>

                                                  <div id="experienceFields_{{ $index }}"
                                                      class="experience-fields"
                                                      style="{{ $employment->employed_as == '1' ? 'display: block;' : 'display: none;' }}">





                                                      <div class="row">
                                                          <div class="col-xxl-4">
                                                              <div class="form-group ">
                                                                  <label
                                                                      for="past_organisation_name_{{ $index }}"
                                                                      class=" form-label">Organisation
                                                                      Name <span class="text-danger">*</span></label>
                                                                  <input type="text" class="form-control"
                                                                      id="past_organisation_name_{{ $index }}"
                                                                      name="past_organisation_name[]"
                                                                      value="{{ $employment->past_organisation_name }}"
                                                                      placeholder="Enter Organisation Name">
                                                              </div>
                                                          </div>



                                                          <div class="col-xxl-4">
                                                              <div class="form-group ">
                                                                  <label for="past_department_{{ $index }}"
                                                                      class=" form-label">Department
                                                                      <span class="text-danger">*</span></label>
                                                                  <input type="text" class="form-control"
                                                                      id="past_department_{{ $index }}"
                                                                      name="past_department[]"
                                                                      value="{{ $employment->past_department }}"
                                                                      placeholder="Enter Department">
                                                              </div>
                                                          </div>

                                                          <div class="col-xxl-4">
                                                              <div class="form-group ">
                                                                  <label for="past_designation_{{ $index }}"
                                                                      class=" form-label">Designation
                                                                      <span class="text-danger">*</span></label>
                                                                  <input type="text" class="form-control"
                                                                      id="past_designation_{{ $index }}"
                                                                      name="past_designation[]"
                                                                      value="{{ $employment->past_designation }}"
                                                                      placeholder="Enter Designation">
                                                              </div>
                                                          </div>
                                                      </div>

                                                      <div class="row">

                                                          <div class="col-xxl-4">
                                                              <div class="form-group ">
                                                                  <label for="past_role_{{ $index }}"
                                                                      class=" form-label">Role <span
                                                                          class="text-danger">*</span></label>
                                                                  <input type="text" class="form-control"
                                                                      id="past_role_{{ $index }}"
                                                                      name="past_role[]"
                                                                      value="{{ $employment->past_role }}"
                                                                      placeholder="Enter Role">
                                                              </div>
                                                          </div>



                                                          <div class="col-xxl-4">
                                                              <div class="form-group ">
                                                                  <label for="past_location_{{ $index }}"
                                                                      class=" form-label">Location
                                                                      <span class="text-danger">*</span></label>
                                                                  <input type="text" class="form-control"
                                                                      id="past_location_{{ $index }}"
                                                                      name="past_location[]"
                                                                      value="{{ $employment->past_location }}"
                                                                      placeholder="Enter Location">
                                                              </div>
                                                          </div>
                                                          <div class="col-xxl-4">
                                                              <div class="form-group ">
                                                                  <label for="past_annual_ctc_{{ $index }}"
                                                                      class=" form-label">Annual
                                                                      CTC (₹) <span
                                                                          class="text-danger">*</span></label>
                                                                  <input type="text"
                                                                      class="form-control past-annual-ctc"
                                                                      name="past_annual_ctc[]"
                                                                      value="{{ $employment->past_annual_ctc }}"
                                                                      placeholder="Enter Annual CTC">
                                                                  <div class="invalid-feedback">
                                                                      Please enter a valid amount (e.g., ₹1,00,000.00)
                                                                  </div>
                                                              </div>
                                                          </div>


                                                      </div>


                                                      <div class="row ">
                                                          <div class="col-xxl-4">
                                                              <div class="form-group">
                                                                  <label for="past_from_date_{{ $index }}"
                                                                      class=" form-label">Period
                                                                      of Service From <span
                                                                          class="text-danger">*</span></label>
                                                                  <input type="date" class="form-control"
                                                                      id="past_from_date_{{ $index }}"
                                                                      name="past_from_date[]"
                                                                      value="{{ $employment->past_from_date }}">
                                                              </div>
                                                          </div>
                                                          <div class="col-xxl-4">
                                                              <div class="form-group">
                                                                  <label for="past_to_date_{{ $index }}"
                                                                      class=" form-label">To
                                                                      <span class="text-danger">*</span></label>
                                                                  <input type="date" class="form-control"
                                                                      id="past_to_date_{{ $index }}"
                                                                      name="past_to_date[]"
                                                                      value="{{ $employment->past_to_date }}">
                                                              </div>
                                                          </div>


                                                          <div class="col-xxl-4">
                                                              <div class="form-group ">
                                                                  <label for="has_uan_{{ $index }}"
                                                                      class="form-label">Do
                                                                      you have
                                                                      UAN (PF) number </label>
                                                                  <select class="form-select"
                                                                      id="has_uan_{{ $index }}"
                                                                      name="has_uan[]">
                                                                      <option value="">Select Option</option>
                                                                      <option value="1"
                                                                          {{ $employment->has_uan == '1' ? 'selected' : '' }}>
                                                                          Yes</option>
                                                                      <option value="0"
                                                                          {{ $employment->has_uan == '0' ? 'selected' : '' }}>
                                                                          No</option>
                                                                  </select>
                                                              </div>
                                                          </div>
                                                      </div>



                                                      <div class="form-group  uan-number-field"
                                                          style="{{ $employment->has_uan == '1' ? 'display: block;' : 'display: none;' }}">
                                                          <label for="uan_number_{{ $index }}"
                                                              class=" form-label">UAN Number <span
                                                                  class="text-danger">*</span></label>
                                                          <input type="text" class="form-control uan-input"
                                                              id="uan_number_{{ $index }}"
                                                              name="uan_number[]"
                                                              value="{{ $employment->uan_number }}"
                                                              placeholder="Enter 12-digit UAN Number" maxlength="12"
                                                              title="Enter a valid 12-digit UAN number">
                                                          <div class="invalid-feedback">
                                                              Please enter a valid 12-digit UAN number.
                                                          </div>
                                                      </div>



                                                  </div>
                                                  @if ($index > 0)
                                                      <div class="text-end">
                                                          <button type="button"
                                                              class="btn btn-outline-danger btn-sm remove-employment">
                                                              <i class="bi bi-trash me-1"></i>Remove
                                                          </button>
                                                      </div>
                                                  @endif
                                              </div>
                                          @endforeach
                                      @else
                                          <div class="past-employment-entry mb-4 border-bottom pb-4">
                                              <div class="form-group mb-3">
                                                  <label for="employed_as" class=" form-label">Employed As <span
                                                          class="text-danger">*</span></label>
                                                  <select class="form-select" id="employed_as" name="employed_as[]"
                                                      required>
                                                      <option value="">Select Type</option>
                                                      <option value="0">New</option>
                                                      <option value="1">Experienced</option>
                                                  </select>
                                              </div>

                                              <div id="experienceFields" class="experience-fields"
                                                  style="display: none;">
                                                  <div class="row">
                                                      <div class="col-xxl-4">
                                                          <div class="form-group ">
                                                              <label for="past_organisation_name"
                                                                  class=" form-label">Organisation Name <span
                                                                      class="text-danger">*</span></label>
                                                              <input type="text" class="form-control"
                                                                  id="past_organisation_name"
                                                                  name="past_organisation_name[]"
                                                                  placeholder="Enter Organisation Name">
                                                          </div>
                                                      </div>
                                                      <div class="col-xxl-4">
                                                          <div class="form-group ">
                                                              <label for="past_department"
                                                                  class="form-label">Department
                                                              </label>
                                                              <input type="text" class="form-control"
                                                                  id="past_department" name="past_department[]"
                                                                  placeholder="Enter Department">
                                                          </div>
                                                      </div>


                                                      <div class="col-xxl-4">
                                                          <div class="form-group ">
                                                              <label for="past_designation"
                                                                  class="form-label">Designation
                                                                  <span class="text-danger">*</span></label>
                                                              <input type="text" class="form-control"
                                                                  id="past_designation" name="past_designation[]"
                                                                  placeholder="Enter Designation">
                                                          </div>
                                                      </div>
                                                  </div>

                                                  <div class="row">

                                                      <div class="col-xxl-4">
                                                          <div class="form-group ">
                                                              <label for="past_role" class="form-label">Role <span
                                                                      class="text-danger">*</span></label>
                                                              <input type="text" class="form-control"
                                                                  id="past_role" name="past_role[]"
                                                                  placeholder="Enter Role">
                                                          </div>
                                                      </div>
                                                      <div class="col-xxl-4">
                                                          <div class="form-group ">
                                                              <label for="past_location" class="form-label">Location
                                                              </label>
                                                              <input type="text" class="form-control"
                                                                  id="past_location" name="past_location[]"
                                                                  placeholder="Enter Location">
                                                          </div>
                                                      </div>
                                                      <div class="col-xxl-4">
                                                          <div class="form-group ">
                                                              <label for="past_annual_ctc" class="form-label">Annual
                                                                  CTC
                                                                  (₹)
                                                              </label>
                                                              <input type="text"
                                                                  class="form-control past-annual-ctc"
                                                                  name="past_annual_ctc[]"
                                                                  placeholder="Enter Annual CTC">
                                                              <div class="invalid-feedback">
                                                                  Please enter a valid amount (e.g., ₹1,00,000.00)
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>


                                                  <div class="row ">
                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="past_from_date" class="form-label">Period
                                                                  of
                                                                  Service From <span
                                                                      class="text-danger">*</span></label>
                                                              <input type="date" class="form-control"
                                                                  id="past_from_date" name="past_from_date[]">
                                                          </div>
                                                      </div>
                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="past_to_date" class="form-label">To <span
                                                                      class="text-danger">*</span></label>
                                                              <input type="date" class="form-control"
                                                                  id="past_to_date" name="past_to_date[]">
                                                          </div>
                                                      </div>
                                                      <div class="col-xxl-4">
                                                          <div class="form-group ">
                                                              <label for="has_uan" class="form-label">Do you have
                                                                  UAN (PF) number </label>
                                                              <select class="form-select" id="has_uan"
                                                                  name="has_uan[]">
                                                                  <option value="">Select Option</option>
                                                                  <option value="1">Yes</option>
                                                                  <option value="0">No</option>
                                                              </select>
                                                          </div>
                                                      </div>
                                                  </div>


                                                  <div class="form-group  uan-number-field" style="display: none;">
                                                      <label for="uan_number" class="form-label">UAN Number </label>
                                                      <input type="text" class="form-control uan-input"
                                                          id="uan_number" name="uan_number[]"
                                                          placeholder="Enter 12-digit UAN Number" maxlength="12"
                                                          title="Enter a valid 12-digit UAN number">
                                                      <div class="invalid-feedback">
                                                          Please enter a valid 12-digit UAN number.
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      @endif
                                  </div>
                              </div>
                          </div>

                          <div class="d-flex justify-content-between mt-3">
                              <button type="button" name="previous"
                                  class="btn btn-secondary previous btn-sm action-button">
                                  <i class="bi bi-arrow-left me-2"></i>Previous
                              </button>
                              <button type="button" name="next" class="btn btn-primary next btn-sm action-button"
                                  data-step="4">
                                  Next <i class="bi bi-arrow-right ms-2"></i>
                              </button>
                          </div>
                      </fieldset>

                      <!-- Fieldset 5: Current Employment -->
                      <fieldset style="{{ $step != 5 ? 'display:none;' : '' }}">
                          <div class="card">
                              <div class="card-header">
                                  <h4 class="mb-0">Current Employment Details</h4>
                              </div>
                              <div class="card-body ">



                                  <div class="row ">

                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="cur_department" class="form-label">Department <span
                                                      class="text-danger">*</span></label>
                                              <div class="input-group">
                                                  <input type="text" class="form-control" id="cur_department"
                                                      name="dep_name"
                                                      value="{{ old('dep_name', $employee->Departmentid->dep_name ?? '') }}"
                                                      placeholder="Search department ..." required>
                                                  <input type="hidden" id="dep_id" name="cur_department"
                                                      value="{{ old('cur_department', $employee->dep_id ?? '') }}">

                                                  <button type="button" class="btn btn-outline-primary"
                                                      data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                                                      Add
                                                  </button>
                                              </div>
                                              <ul id="curdepartment-list" class="list-group" style="display: none;">
                                              </ul>
                                          </div>
                                      </div>




                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="cur_designation" class="form-label">Designation <span
                                                      class="text-danger">*</span></label>
                                              <div class="input-group">
                                                  <input type="text" class="form-control" id="cur_designation"
                                                      name="des_name"
                                                      value="{{ old('des_name', $employee->Designationid->des_name ?? '') }}"
                                                      placeholder="Search designation ..." required>
                                                  <input type="hidden" id="des_id" name="cur_designation"
                                                      value="{{ old('cur_designation', $employee->des_id ?? '') }}">
                                                  <button type="button" class="btn btn-outline-primary"
                                                      data-bs-toggle="modal" data-bs-target="#addDesignationModal">
                                                      Add
                                                  </button>
                                              </div>
                                              <ul id="curdesignation-list" class="list-group" style="display: none;">
                                              </ul>
                                          </div>
                                      </div>


                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="cur_location" class="form-label">Location <span
                                                      class="text-danger">*</span></label>
                                              <div class="input-group">
                                                  <input type="text" class="form-control" id="cur_location"
                                                      name="branch_name"
                                                      value="{{ old('branch_name', $employee->Branchid->branch_name ?? '') }}"
                                                      placeholder="Search location ..." required>
                                                  <input type="hidden" id="branch_id" name="cur_location"
                                                      value="{{ old('cur_location', $employee->branch_id ?? '') }}">
                                                  <button type="button" class="btn btn-outline-primary"
                                                      data-bs-toggle="modal"
                                                      data-bs-target="#addLocationModal">Add</button>
                                              </div>
                                              <ul id="curlocation-list" class="list-group" style="display: none;">
                                              </ul>
                                          </div>
                                      </div>




                                  </div>
                                  <div class="row ">





                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="cur_annual_ctc" class="form-label">Annual CTC (₹) <span
                                                      class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="cur_annual_ctc"
                                                  name="cur_annual_ctc"
                                                  value="{{ old('cur_annual_ctc', $employee->cur_annual_ctc ?? '') }}"
                                                  placeholder="Enter Annual CTC" required>
                                              <div class="invalid-feedback">
                                                  Please enter a valid annual CTC (e.g., ₹1,00,000.00)
                                              </div>
                                          </div>
                                      </div>


                                      <div class="col-xxl-4">
                                          <div class="form-group">
                                              <label for="jobtype" class="form-label">Job Type<span
                                                      class="text-danger">*</span></label>
                                              <div class="input-group">
                                                  <input type="text" class="form-control" id="jobtype"
                                                      name="jobtype_name"
                                                      value="{{ old('jobtype_name', $employee->jobTypeid->jobtype_name ?? '') }}"
                                                      placeholder="Search jobtype ...">
                                                  <input type="hidden" id="jobtype_id" name="jobtype"
                                                      value="{{ old('jobtype', $employee->jobtype_id ?? '') }}">

                                              </div>
                                              <ul id="jobtype-list" class="list-group" style="display: none;">
                                              </ul>
                                          </div>
                                      </div>

                                  </div>



                                  <div class="provision-period-section" id="provision">
                                      <h5> Probation Period</h5>
                                      <div class="row">
                                          <div class="col-xxl-4">
                                              <div class="form-group">
                                                  <label for="dojprovision_from_date"
                                                      class="form-label">DateOfJoin(From)<span
                                                    class="text-danger ">*</span></label>
                                                  <input type="date" class="form-control"
                                                      id="dojprovision_from_date"
                                                      value="{{ old('dojprovision_from_date', $employee->dojprovision_from_date ?? '') }}"
                                                      name="dojprovision_from_date">
                                              </div>
                                          </div>
                                          <div class="col-xxl-4">
                                              <div class="form-group">
                                                  <label for="provision_to_date" class="form-label">To<span
                                                    class="text-danger ">*</span></label>
                                                  <input type="date" class="form-control"
                                                      id="provision_to_date"
                                                      value="{{ old('provision_to_date', $employee->provision_to_date ?? '') }}"
                                                      name="provision_to_date">
                                              </div>
                                          </div>
                                          <div class="col-xxl-4">
                                              <div class="form-group">
                                                  <label for="provision_months" class="form-label">In
                                                      Month</label>
                                                  <input type="text" class="form-control" id="provision_months"
                                                      value="{{ old('inmonth', $employee->inmonth ?? '') }}"
                                                      name="inmonth" readonly>
                                              </div>
                                          </div>
                                      </div>

                                  </div>






                              </div>
                          </div>

                          <div class="d-flex justify-content-between mt-3">
                              <button type="button" name="previous"
                                  class="btn btn-secondary previous btn-sm action-button"><i
                                      class="bi bi-arrow-left me-2"></i>Previous</button>
                              <button type="button" name="next"
                                  class="btn btn-primary next btn-sm action-button" data-step="5">Next <i
                                      class="bi bi-arrow-right ms-2"></i></button>
                          </div>
                      </fieldset>

                      <!-- Fieldset 6: Professional References -->
                      <fieldset style="{{ $step != 6 ? 'display:none;' : '' }}">
                          <div class="card">
                              <div class="card-header">
                                  <h4 class="mb-0">Professional References</h4>
                              </div>
                              <div class="card-body">
                                  <div id="referencesContainer">
                                      <div class="text-end">
                                          <button type="button" id="addReference"
                                              class="btn btn-outline-primary mb-3">
                                              <i class="bi bi-plus-circle me-2"></i>Add
                                          </button>
                                      </div>

                                      @if (count($references) > 0)
                                          @foreach ($references as $index => $reference)
                                              <div class="reference-entry ">
                                                  <div class="row ">
                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="ref_name_{{ $index }}"
                                                                  class="form-label">Reference
                                                                  Name <span class="text-danger">*</span></label>
                                                              <input type="text" class="form-control"
                                                                  id="ref_name_{{ $index }}"
                                                                  name="ref_name[]"
                                                                  value="{{ $reference->ref_name }}"
                                                                  placeholder="Enter name" required>
                                                          </div>
                                                      </div>
                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="ref_organization_name_{{ $index }}"
                                                                  class="form-label">Organization Name <span
                                                                      class="text-danger">*</span></label>
                                                              <input type="text" class="form-control"
                                                                  id="ref_organization_name_{{ $index }}"
                                                                  name="ref_organization_name[]"
                                                                  value="{{ $reference->ref_organization_name }}"
                                                                  placeholder="Enter organization name" required>
                                                          </div>
                                                      </div>
                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="ref_designation_{{ $index }}"
                                                                  class="form-label">Designation <span
                                                                      class="text-danger">*</span></label>
                                                              <input type="text" class="form-control"
                                                                  id="ref_designation_{{ $index }}"
                                                                  name="ref_designation[]"
                                                                  value="{{ $reference->ref_designation }}"
                                                                  placeholder="Enter designation" required>
                                                          </div>
                                                      </div>
                                                  </div>

                                                  <div class="row ">
                                                      <div class="col-xxl-4">
                                                          <div class="form-group">
                                                              <label for="mobile_no_{{ $index }}"
                                                                  class="form-label">Mobile
                                                                  No</label>
                                                              <input type="tel" class="form-control"
                                                                  id="mobile_no_{{ $index }}"
                                                                  name="mobile_no[]"
                                                                  value="{{ $reference->mobile_no }}"
                                                                  placeholder="Enter mobile number" maxlength="13" required>
                                                          </div>
                                                      </div>
                                                    <div class="col-xxl-4">
    <div class="form-group">
        <label for="email_ref_{{ $index }}" class="form-label">Email</label>
        <input type="email" class="form-control" id="email_ref_{{ $index }}"
            name="email_ref[]" value="{{ $reference->email_ref }}"
            placeholder="Enter email">
    </div>
</div>
                                                  </div>
                                                  @if ($index > 0)
                                                      <div class="text-end">
                                                          <button type="button"
                                                              class="btn btn-outline-danger btn-sm remove-reference">
                                                              <i class="bi bi-trash me-1"></i>Remove
                                                          </button>
                                                      </div>
                                                  @endif
                                              </div>
                                          @endforeach
                                      @else
                                          <div class="reference-entry ">
                                              <div class="row ">
                                                  <div class="col-xxl-4">
                                                      <div class="form-group">
                                                          <label for="ref_name" class="form-label">Reference Name
                                                              <span class="text-danger">*</span></label>
                                                          <input type="text" class="form-control"
                                                              id="ref_name" name="ref_name[]"
                                                              placeholder="Enter name" required>
                                                      </div>
                                                  </div>
                                                  <div class="col-xxl-4">
                                                      <div class="form-group">
                                                          <label for="ref_organization_name"
                                                              class="form-label">Organization
                                                              Name <span class="text-danger">*</span></label>
                                                          <input type="text" class="form-control"
                                                              id="ref_organization_name"
                                                              name="ref_organization_name[]"
                                                              placeholder="Enter organization name" required>
                                                      </div>
                                                  </div>
                                                  <div class="col-xxl-4">
                                                      <div class="form-group">
                                                          <label for="ref_designation"
                                                              class="form-label">Designation
                                                              <span class="text-danger">*</span></label>
                                                          <input type="text" class="form-control"
                                                              id="ref_designation" name="ref_designation[]"
                                                              placeholder="Enter designation" required>
                                                      </div>
                                                  </div>
                                              </div>

                                              <div class="row ">
                                                  <div class="col-xxl-4">
                                                      <div class="form-group">
                                                          <label for="mobile_no" class="form-label">Mobile
                                                              No <span class="text-danger">*</span></label>
                                                          <input type="tel" class="form-control"
                                                              id="mobile_no" name="mobile_no[]"
                                                              placeholder="Enter mobile number" maxlength="13">
                                                      </div>
                                                  </div>


                                                 <div class="col-xxl-4">
    <div class="form-group">
        <label for="email_ref" class="form-label">Email</label>
        <input type="email" class="form-control email-input" id="email_ref"
            name="email_ref[]" placeholder="Enter email">
        <small class="text-danger error-message" style="display:none;">Invalid email format</small>
    </div>
</div>



                                              </div>



                                          </div>
                                      @endif
                                  </div>
                              </div>
                          </div>

                          <div class="d-flex justify-content-between mt-3">
                              <button type="button" name="previous"
                                  class="btn btn-sm btn-secondary previous action-button"><i
                                      class="bi bi-arrow-left me-2"></i>Previous</button>
                              <button type="submit" class="btn btn-sm btn-primary action-button">Update <i
                                      class="bi bi-check-circle ms-2"></i></button>
                          </div>
                      </fieldset>
                  </div>
              </form>





          </div>
      </div>

  </x-layout>
  <!-- Add these to your HTML head section -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    // Completely disable HTML5 validation
document.addEventListener('DOMContentLoaded', function() {
    // Override the default validation behavior
    const originalCheckValidity = HTMLFormElement.prototype.checkValidity;
    HTMLFormElement.prototype.checkValidity = function() {
        return true;
    };

    const originalReportValidity = HTMLFormElement.prototype.reportValidity;
    HTMLFormElement.prototype.reportValidity = function() {
        return true;
    };

    // Also override for individual inputs
    const originalInputCheckValidity = HTMLInputElement.prototype.checkValidity;
    HTMLInputElement.prototype.checkValidity = function() {
        return true;
    };

    const originalInputReportValidity = HTMLInputElement.prototype.reportValidity;
    HTMLInputElement.prototype.reportValidity = function() {
        return true;
    };
});
</script>

  <script>
      // Global loading screen element
      const loadingScreen = document.createElement('div');
      loadingScreen.id = 'loading-screen';
      loadingScreen.style.position = 'fixed';
      loadingScreen.style.top = '0';
      loadingScreen.style.left = '0';
      loadingScreen.style.width = '100%';
      loadingScreen.style.height = '100%';
      loadingScreen.style.backgroundColor = 'rgba(0,0,0,0.5)';
      loadingScreen.style.display = 'flex';
      loadingScreen.style.justifyContent = 'center';
      loadingScreen.style.alignItems = 'center';
      loadingScreen.style.zIndex = '9999';
      loadingScreen.style.display = 'none';

      const spinner = document.createElement('div');
      spinner.className = 'spinner-border text-light';
      spinner.style.width = '3rem';
      spinner.style.height = '3rem';
      spinner.setAttribute('role', 'status');

      const srOnly = document.createElement('span');
      srOnly.className = 'sr-only';


      spinner.appendChild(srOnly);
      loadingScreen.appendChild(spinner);
      document.body.appendChild(loadingScreen);




      function initPercentageInputs() {
          document.querySelectorAll('.percentage-input').forEach(input => {
              // Remove any existing event listeners to avoid duplicates
              input.removeEventListener('input', handlePercentageInput);
              input.removeEventListener('blur', handlePercentageBlur);

              // Add new event listeners
              input.addEventListener('input', handlePercentageInput);
              input.addEventListener('blur', handlePercentageBlur);
          });
      }

      function handlePercentageInput(e) {
          let value = this.value;

          // Allow only numbers and decimal point
          value = value.replace(/[^0-9.]/g, '');

          // Ensure only one decimal point
          const decimalSplit = value.split('.');
          if (decimalSplit.length > 2) {
              value = decimalSplit[0] + '.' + decimalSplit[1];
          }

          // Limit to 2 decimal places
          if (decimalSplit.length > 1) {
              value = decimalSplit[0] + '.' + decimalSplit[1].slice(0, 2);
          }

          // Ensure value doesn't exceed 100
          if (parseFloat(value) > 100) {
              value = '100';
          }

          this.value = value;
      }

      function handlePercentageBlur() {
          if (this.value) {
              // Format to 2 decimal places
              if (this.value.includes('.')) {
                  const parts = this.value.split('.');
                  this.value = `${parts[0]}.${parts[1].padEnd(2, '0').slice(0, 2)}`;
              } else {
                  this.value = `${this.value}.00`;
              }
          }

          // Validate the final value (0 to 100, up to 2 decimal places, no %)
          const pattern = /^(100(\.00)?|\d{1,2}(\.\d{1,2})?|99\.\d{1,2})$/;
          if (!pattern.test(this.value)) {
              this.classList.add('is-invalid');
          } else {
              this.classList.remove('is-invalid');
          }
      }

      // Initialize on page load
      document.addEventListener('DOMContentLoaded', function() {
          initPercentageInputs();
      });



      // Email validation
      document.querySelectorAll('.email-input').forEach(function(input) {
          input.addEventListener('input', function() {
              const email = input.value.trim();
              const errorEl = input.parentElement.querySelector('.error-message');
              const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

              if (email === '' || valid) {
                  errorEl.style.display = 'none';
                  input.classList.remove('is-invalid');
              } else {
                  errorEl.style.display = 'block';
                  input.classList.add('is-invalid');
                  setTimeout(() => {
                      input.classList.remove('is-invalid');
                      errorEl.style.display = 'none';
                  }, 3000);

                  // Toastify({
                  //     text: "Please enter a valid email address",
                  //     duration: 3000,
                  //     gravity: "top",
                  //     position: "right",
                  //     backgroundColor: "linear-gradient(to right, #ff5f6d, #ff7b81)",
                  //     stopOnFocus: true
                  // }).showToast();
              }
          });
      });



      // Required field validation

      document.addEventListener('DOMContentLoaded', function() {
          // Format currency inputs to INR
          function formatToINR(input) {
              let cleaned = input.replace(/[^\d.]/g, '');
              if (cleaned === '') return '';

              let parts = cleaned.split('.');
              let integerPart = parts[0];
              let decimalPart = parts.length > 1 ? '.' + parts[1].slice(0, 2) : '';

              let lastThree = integerPart.slice(-3);
              let otherNumbers = integerPart.slice(0, -3);
              if (otherNumbers !== '') {
                  lastThree = ',' + lastThree;
              }
              let formatted = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;

              return '₹' + formatted + decimalPart;
          }

          // Apply INR formatting to past annual CTC
          document.querySelectorAll('.past-annual-ctc').forEach(function(input) {
              input.addEventListener('input', function() {
                  let pos = this.selectionStart;
                  let originalLength = this.value.length;
                  this.value = formatToINR(this.value);
                  let newLength = this.value.length;
                  this.setSelectionRange(pos + (newLength - originalLength), pos + (newLength -
                      originalLength));
              });

              input.addEventListener('blur', function() {
                  let val = this.value.replace(/[^\d.]/g, '');
                  if (!isNaN(val) && val !== '') {
                      this.value = formatToINR(val);
                  }
              });
          });

          // Current Annual CTC
          const ctcInput = document.getElementById('cur_annual_ctc');
          if (ctcInput) {
              ctcInput.addEventListener('input', function() {
                  let pos = this.selectionStart;
                  let originalLength = this.value.length;
                  this.value = formatToINR(this.value);
                  let newLength = this.value.length;
                  this.setSelectionRange(pos + (newLength - originalLength), pos + (newLength -
                      originalLength));
              });

              ctcInput.addEventListener('blur', function() {
                  let val = this.value.replace(/[^\d.]/g, '');
                  if (!isNaN(val) && val !== '') {
                      this.value = formatToINR(val);
                  }
              });
          }

          // UAN validation
          document.querySelectorAll('.uan-input').forEach(function(input) {
              input.addEventListener('keydown', function(e) {
                  const allowed = ['Backspace', 'ArrowLeft', 'ArrowRight', 'Tab', 'Delete'];
                  if (!allowed.includes(e.key) && !(e.key >= '0' && e.key <= '9')) e
                      .preventDefault();
              });

              input.addEventListener('input', function() {
                  this.value = this.value.replace(/\D/g, '').slice(0, 12);
              });
          });

          // Aadhaar formatting
          const aadhaarInput = document.getElementById('aadhaar_no');
          if (aadhaarInput) {
              aadhaarInput.addEventListener('input', function() {
                  let value = this.value.replace(/\D/g, '');
                  if (value.length > 0) value = value.match(/.{1,4}/g).join(' ');
                  this.value = value.substring(0, 14);
              });

              aadhaarInput.addEventListener('keypress', function(e) {
                  if ([8, 46, 9, 37, 38, 39, 40].includes(e.keyCode)) return;
                  if (e.keyCode < 48 || e.keyCode > 57) e.preventDefault();
              });
          }

          // PAN validation
          const panInput = document.getElementById('pancard_no');
          if (panInput) {
              panInput.addEventListener('input', function() {
                  this.value = this.value.toUpperCase();
              });

              panInput.addEventListener('keypress', function(e) {
                  const len = this.value.length;
                  const key = e.key.toUpperCase();
                  if (e.keyCode === 8 || e.keyCode === 46 || e.keyCode === 9) return;

                  if (len < 5 && !/[A-Z]/.test(key)) e.preventDefault();
                  else if (len >= 5 && len < 9 && !/[0-9]/.test(key)) e.preventDefault();
                  else if (len === 9 && !/[A-Z]/.test(key)) e.preventDefault();
                  else if (len >= 10) e.preventDefault();
              });
          }


















function validatePhoneNumber(input) {
    const iti = window.intlTelInputGlobals.getInstance(input);
    if (!iti) return { isValid: false, message: "Phone input not initialized" };

    const countryCode = `+${iti.getSelectedCountryData().dialCode}`;
    const countryCodeDigits = countryCode.replace(/\D/g, '');
    let inputVal = input.value;

    if (!inputVal || inputVal === countryCode) {
        return { isValid: false, message: "Phone number is required" };
    }

    let allDigits = inputVal.replace(/\D/g, '');

    if (allDigits.startsWith(countryCodeDigits)) {
        allDigits = allDigits.substring(countryCodeDigits.length);
    }

    // if (allDigits.length !== 10) {
    //     return {
    //         isValid: false,
    //         message: `Phone number must have exactly 10 digits after country code (found ${allDigits.length} digits)`
    //     };
    // }

    return { isValid: true };
}

function validateRequiredFields(fieldset) {
    let isValid = true;
    const missingFields = [];

    // Check if this is Professional References fieldset
    const isProfessionalReferences = fieldset.id === 'fieldset-6' ||
                                     fieldset.querySelector('#ref_name') !== null;

    fieldset.querySelectorAll('[required]').forEach(field => {
        // Skip email fields in Professional References
        if (isProfessionalReferences && (field.type === 'email' || field.name.includes('email_ref'))) {
            return; // Skip validation for email in professional references
        }

        let fieldLabel = field.labels?.[0]?.textContent?.trim() || field.name;
        fieldLabel = fieldLabel.replace(/\*+$/, '').replace(':', '').trim();

        if (field.type === 'radio') {
            const radios = document.querySelectorAll(`[name="${field.name}"]`);
            if (![...radios].some(r => r.checked)) {
                radios.forEach(r => r.classList.add('is-invalid'));
                missingFields.push(fieldLabel);
                isValid = false;
            }
 } else if (field.type === 'tel' || field.name.includes('mobile') || field.name.includes('contact')) {
    // For mobile numbers, validate 10 digits
    const iti = window.intlTelInputGlobals.getInstance(field);
    if (iti) {
        const countryCode = iti.getSelectedCountryData().dialCode;
        const countryCodeDigits = countryCode.replace(/\D/g, '');
        let inputVal = field.value.replace(/\D/g, '');

        if (!inputVal || inputVal === countryCodeDigits) {
            field.classList.add('is-invalid');
            field.style.borderColor = '#dc3545';
            field.style.boxShadow = '0 0 0 0.25rem rgba(220, 53, 69, 0.25)';
            setTimeout(() => {
                field.classList.remove('is-invalid');
                field.style.borderColor = '';
                field.style.boxShadow = '';
            }, 3000);
            missingFields.push(fieldLabel || field.name);
            isValid = false;
        } else {
            // Extract local number
            let localNumber = inputVal;
            if (inputVal.startsWith(countryCodeDigits)) {
                localNumber = inputVal.substring(countryCodeDigits.length);
            }

            // Check for exactly 10 digits
            if (localNumber.length !== 10) {
                field.classList.add('is-invalid');
                field.style.borderColor = '#dc3545';
                field.style.boxShadow = '0 0 0 0.25rem rgba(220, 53, 69, 0.25)';
                setTimeout(() => {
                    field.classList.remove('is-invalid');
                    field.style.borderColor = '';
                    field.style.boxShadow = '';
                }, 3000);
                missingFields.push(`${fieldLabel} - Must be 10 digits (found ${localNumber.length})`);
                isValid = false;
            }
        }
    } else {
        // Fallback for regular tel inputs
        const phoneDigits = field.value.replace(/\D/g, '');
        if (!field.value.trim() || phoneDigits.length !== 10) {
            field.classList.add('is-invalid');
            field.style.borderColor = '#dc3545';
            field.style.boxShadow = '0 0 0 0.25rem rgba(220, 53, 69, 0.25)';
            setTimeout(() => {
                field.classList.remove('is-invalid');
                field.style.borderColor = '';
                field.style.boxShadow = '';
            }, 3000);
            missingFields.push(`${fieldLabel} - Must be 10 digits`);
            isValid = false;
        }
    }
}
        else {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                missingFields.push(fieldLabel);
                isValid = false;
            }
        }
    });

    if (!isValid && missingFields.length > 0) {
        Toastify({
            text: missingFields.length === 1
                ? `Please fill the mandatory field: ${missingFields[0]}`
                : `Please fill the mandatory fields: ${missingFields.join(', ')}`,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#EF4444",
            offset: {
                y: 65
            },
            style: {
                boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                borderRadius: "8px",
                fontFamily: "'Inter', sans-serif",
                fontSize: "14px",
                width: "350px"
            },
            stopOnFocus: true
        }).showToast();

        // Focus on first invalid field
        const firstInvalid = fieldset.querySelector('.is-invalid');
        if (firstInvalid) {
            $('html, body').animate({
                scrollTop: firstInvalid.offsetTop - 100
            }, 500);
            firstInvalid.focus();
        }
    }

    return isValid;
}
function initializePhoneInput(input) {
    // Check if already initialized
    if (input.dataset.itiInitialized) return;

    // Get current value if it exists
    const currentValue = input.value.trim();
    let initialValue = currentValue;

    // If value has country code, keep it
    const iti = window.intlTelInput(input, {
        initialCountry: "in",
        separateDialCode: true,
        nationalMode: false,
        autoPlaceholder: "off",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
    });

    input.dataset.itiInitialized = "true";

    // If there's existing data, set it
    if (initialValue) {
        iti.setNumber(initialValue);
    }

    // Set initial validation state
    validatePhoneInputOnBlur(input, iti);

    // Add validation on blur
    input.addEventListener('blur', function() {
        validatePhoneInputOnBlur(input, iti);
    });

    // Add validation on input to prevent wrong number of digits
    input.addEventListener('input', function(e) {
        validatePhoneInputOnInput(input, iti, e);
    });
}

// Validation function for blur event
function validatePhoneInputOnBlur(input, iti) {
    const countryCode = iti.getSelectedCountryData().dialCode;
    let digits = input.value.replace(/\D/g, '');

    if (digits.startsWith(countryCode)) {
        digits = digits.substring(countryCode.length);
    }

    // if (digits.length !== 10 && digits.length > 0) {
    //     input.classList.add('is-invalid');
    //     Toastify({
    //         text: "Phone number must be exactly 10 digits",
    //         duration: 3000,
    //         gravity: "top",
    //         position: "right",
    //         backgroundColor: "#EF4444"
    //     }).showToast();
    // } else if (digits.length === 10) {
    //     input.classList.remove('is-invalid');
    //     // Ensure proper formatting
    //     input.value = `+${countryCode}${digits}`;
    // }
}

// Validation function for input event
function validatePhoneInputOnInput(input, iti, e) {
    const countryCode = iti.getSelectedCountryData().dialCode;
    const countryCodeDigits = countryCode.replace(/\D/g, '');
    let digits = input.value.replace(/\D/g, '');

    // Remove country code if present
    if (digits.startsWith(countryCodeDigits)) {
        digits = digits.substring(countryCodeDigits.length);
    }

    // Limit to 10 digits
    if (digits.length > 10) {
        digits = digits.substring(0, 10);
    }

    // Reconstruct value with country code
    input.value = `+${countryCode}${digits}`;

    // Move cursor to end
    setTimeout(() => {
        input.setSelectionRange(input.value.length, input.value.length);
    }, 0);
}
// Apply phone input initialization to all phone fields
document.querySelectorAll('#personal_mobile, #mobile_no, #emergency_contact').forEach(input => {
    initializePhoneInput(input);
});

// Also initialize phone inputs for dynamically added reference entries
$(document).on('DOMNodeInserted', '#mobile_no', function() {
    initializePhoneInput(this);
});





















          $(document).ready(function() {
              const employeeId = $('#employee_id').val();
              var current_fs, next_fs, previous_fs;
              var animating;

              toastr.options = {
                  "closeButton": true,
                  "progressBar": true,
                  "positionClass": "toast-top-right",
                  "timeOut": "3000"
              };

              const stepMessages = {
                  1: 'Personal details submitted successfully!',
                  2: 'Family details submitted successfully!',
                  3: 'Education information submitted successfully!',
                  4: 'Past employment details submitted successfully!',
                  5: 'Current employment information submitted successfully!',
                  6: 'Professional references submitted successfully!'
              };

              const previousStepMessages = {
                  1: 'Returned to Personal Details',
                  2: 'Returned to Family Details',
                  3: 'Returned to Education Information',
                  4: 'Returned to Past Employment',
                  5: 'Returned to Current Employment',
                  6: 'Returned to Professional References'
              };

     $(".next").click(function() {
    if (animating) return false;
    animating = true;

    current_fs = $(this).closest('fieldset');
    next_fs = $(this).closest('fieldset').next();
    const step = $(this).data('step');

    if (!validateRequiredFields(current_fs[0])) {
        animating = false;
        return false;
    }

    loadingScreen.style.display = 'flex';

    submitStep(step, current_fs, function(success) {
        loadingScreen.style.display = 'none';

        if (success) {
            toastr.success(stepMessages[step], 'Success');
            const nextIndex = $("fieldset").index(next_fs);

            $("#progressbar li").eq(nextIndex).addClass("active");
            $("#progressbar li").eq($("fieldset").index(current_fs))
                .addClass("completed");

            $(".vertical-progress li").eq(nextIndex).addClass("active");
            $(".vertical-progress li").eq($("fieldset").index(current_fs))
                .addClass("completed");

            next_fs.show();
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now, mx) {
                    next_fs.css({
                        'opacity': 1 - now
                    });
                },
                duration: 300,
                complete: function() {
                    current_fs.hide();
                    animating = false;
                    $('html, body').animate({
                        scrollTop: $("#msform").offset()
                            .top - 20
                    }, 300);
                },
                easing: 'easeInOutQuad'
            });
        } else {
            animating = false;
        }
    });
});

              $(".previous").click(function() {
                  if (animating) return false;
                  animating = true;

                  current_fs = $(this).closest('fieldset');
                  previous_fs = $(this).closest('fieldset').prev();
                  const currentIndex = $("fieldset").index(current_fs);
                  const prevIndex = $("fieldset").index(previous_fs);

                  toastr.info(previousStepMessages[prevIndex + 1], 'Info');

                  $("#progressbar li").eq(currentIndex).removeClass("active");
                  $("#progressbar li").eq(prevIndex).removeClass("completed");

                  $(".vertical-progress li").eq(currentIndex).removeClass("active");
                  $(".vertical-progress li").eq(prevIndex).removeClass("completed");

                  previous_fs.show();
                  current_fs.animate({
                      opacity: 0
                  }, {
                      step: function(now, mx) {
                          previous_fs.css({
                              'opacity': 1 - now
                          });
                      },
                      duration: 300,
                      complete: function() {
                          current_fs.hide();
                          animating = false;
                          $('html, body').animate({
                              scrollTop: $("#msform").offset().top - 20
                          }, 300);
                      },
                      easing: 'easeInOutQuad'
                  });
              });

              function submitStep(step, fieldset, callback) {
                  const formData = new FormData();
                  formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                  formData.append('_method', 'PUT');
                  formData.append('step', step);

                  fieldset.find('input, select, textarea').each(function() {
                      const type = $(this).attr('type');
                      const name = $(this).attr('name');

                      if (!name) return;

                      if (type === 'file') {
                          if ($(this)[0].files[0]) {
                              formData.append(name, $(this)[0].files[0]);
                          }
                      } else if (type === 'radio' || type === 'checkbox') {
                          if ($(this).is(':checked')) {
                              formData.append(name, $(this).val());
                          }
                      } else {
                          formData.append(name, $(this).val());
                      }
                  });

                  $.ajax({
                      url: "{{ route('empupdate', $employee->emp_id) }}",
                      type: "POST",
                      data: formData,
                      processData: false,
                      contentType: false,
                      success: function(response) {
                          if (response.error) {
                              Swal.fire({
                                  icon: 'error',
                                  title: 'Error',
                                  text: response.error,
                                  confirmButtonColor: '#3085d6',
                              });
                              return callback(false);
                          }
                          if (response.employee_id) {
                              $('#employee_id').val(response.employee_id);
                          }
                          callback(true);
                      },
                      error: function(xhr) {
                          let errorMsg = 'An error occurred';
                          if (xhr.responseJSON && xhr.responseJSON.errors) {
                              const errors = xhr.responseJSON.errors;
                              errorMsg = Object.values(errors).flat().join('\n');
                          } else if (xhr.responseJSON && xhr.responseJSON.error) {
                              errorMsg = xhr.responseJSON.error;
                          } else if (xhr.responseText) {
                              errorMsg = xhr.responseText;
                          }


                          // console.error(errorMsg);
                          Toastify({
                              text: 'Please fill  the mandatory  details ',
                              duration: 3000,
                              gravity: "top",
                              position: "right",
                              backgroundColor: "#EF4444",
                              stopOnFocus: true
                          }).showToast();
                          callback(false);
                      }
                  });
              }

            $('#msform').on('submit', function(e) {
    e.preventDefault();

    // Prevent default browser validation for the final submit too
    this.checkValidity = function() { return true; };

    // Validate the last fieldset (Professional References)
    const lastFieldset = $('fieldset').last()[0];
    if (!validateRequiredFields(lastFieldset)) {
        return false;
    }

    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    formData.append('step', 6);

    loadingScreen.style.display = 'flex';

    $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            loadingScreen.style.display = 'none';

            if (response.redirect) {
                window.location.href = response.redirect;
            }
        },
        error: function(xhr) {
            loadingScreen.style.display = 'none';

            let errors = xhr.responseJSON.errors;
            for (let field in errors) {
                let errorMessage = errors[field][0];
                $('[name="' + field + '"]').addClass('is-invalid')
                    .after('<div class="invalid-feedback">' +
                        errorMessage + '</div>');
            }
        }
    });
});

              // Add this inside your DOMContentLoaded event listener
              document.getElementById('dojprovision_from_date')?.addEventListener('change',
                  calculateProvisionPeriod);
              document.getElementById('provision_to_date')?.addEventListener('change',
                  calculateProvisionPeriod);

           function calculateProvisionPeriod() {
    const fromDateStr = document.getElementById('dojprovision_from_date').value;
    const toDateStr = document.getElementById('provision_to_date').value;
    const resultField = document.getElementById('provision_months');

    // Clear field if dates are empty
    if (!fromDateStr || !toDateStr) {
        resultField.value = '';
        return;
    }

    // Parse dates
    const fromDate = new Date(fromDateStr);
    const toDate = new Date(toDateStr);

    // Validate dates
    if (isNaN(fromDate.getTime()) || isNaN(toDate.getTime())) {
        resultField.value = '';
        return;
    }

    if (fromDate > toDate) {
        resultField.value = 'Invalid: End date before start';
        return;
    }

    // Calculate total days difference
    const timeDiff = toDate.getTime() - fromDate.getTime();
    const totalDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

    // Calculate months and remaining days
    let years = toDate.getFullYear() - fromDate.getFullYear();
    let months = toDate.getMonth() - fromDate.getMonth();
    let days = toDate.getDate() - fromDate.getDate();

    // Adjust for negative months
    if (months < 0) {
        years--;
        months += 12;
    }

    // Adjust for negative days
    if (days < 0) {
        months--;
        // Get the number of days in the previous month
        const previousMonth = new Date(toDate.getFullYear(), toDate.getMonth(), 0);
        days += previousMonth.getDate();

        // If months became negative, adjust years
        if (months < 0) {
            years--;
            months += 12;
        }
    }

    // Format the result
    let result = '';
    if (years > 0) {
        result += `${years} Year${years !== 1 ? 's' : ''} `;
    }
    if (months > 0) {
        result += `${months} Month${months !== 1 ? 's' : ''} `;
    }
    if (days > 0 || (years === 0 && months === 0)) {
        result += `${days} Day${days !== 1 ? 's' : ''}`;
    }

    resultField.value = result.trim() || '0 Days';
}
            // OLD FUNCTION - REMOVE THIS
function monthDiff(startDate, endDate) {
    let months;
    months = (endDate.getFullYear() - startDate.getFullYear()) * 12;
    months -= startDate.getMonth();
    months += endDate.getMonth();
    return months <= 0 ? 0 : months;
}
              let pastEmploymentCount = document.querySelectorAll('.past-employment-entry').length;












              function handleEmploymentTypeChange(selectElement) {
                  const selectedValue = selectElement.value;
                  const container = selectElement.closest('.past-employment-entry');
                  const experienceFields = container.querySelector('.experience-fields');

                  if (selectedValue === '1') {
                      experienceFields.style.display = 'block';
                  } else {
                      experienceFields.style.display = 'none';
                      const inputs = experienceFields.querySelectorAll('input, select');
                      inputs.forEach(input => {
                          input.value = '';
                      });

                      if (selectedValue === '0') {
                          const allEntries = document.querySelectorAll('.past-employment-entry');
                          for (let i = 1; i < allEntries.length; i++) {
                              allEntries[i].remove();
                          }
                          pastEmploymentCount = 1;
                          document.querySelectorAll('.remove-employment').forEach(btn => {
                              btn.style.display = 'none';
                          });
                      }
                  }
              }

              function handleUANChange(selectElement) {
                  const selectedValue = selectElement.value;
                  const container = selectElement.closest('.past-employment-entry');
                  const uanField = container.querySelector('.uan-number-field');

                  if (selectedValue === '1') {
                      uanField.style.display = 'block';
                  } else {
                      uanField.style.display = 'none';
                      const input = uanField.querySelector('input');
                      input.value = '';
                  }
              }

              function initializeEntries() {
                  // Initialize employment type change handlers
                  document.querySelectorAll('select[name="employed_as[]"]').forEach(select => {
                      // Set up event listener
                      select.addEventListener('change', function() {
                          handleEmploymentTypeChange(this);
                      });

                      // Trigger initial state
                      handleEmploymentTypeChange(select);
                  });

                  document.querySelectorAll('select[name="has_uan[]"]').forEach(select => {
                      select.addEventListener('change', function() {
                          handleUANChange(this);
                      });
                      handleUANChange(select);
                  });

                  if (document.querySelectorAll('.past-employment-entry').length <= 1) {
                      document.querySelectorAll('.remove-employment').forEach(btn => {
                          btn.style.display = 'none';
                      });
                  }
              }

              document.getElementById('addPastEmployment')?.addEventListener('click', function() {
                  const firstEntryType = document.querySelector(
                          'select[name="employed_as[]"]')
                      ?.value;

                  if (!firstEntryType) {
                      Swal.fire({
                          title: 'Selection Required',
                          text: 'Please select employment type in the first entry first.',
                          icon: 'warning',
                          confirmButtonText: 'OK'
                      });
                      return false;
                  }

                  if (firstEntryType === '0') {
                      Swal.fire({
                          title: 'Cannot Add More',
                          text: 'Please select "Experienced" in the first entry to add more employment entries.',
                          icon: 'warning',
                          confirmButtonText: 'OK'
                      });
                      return false;
                  }

                  if (pastEmploymentCount >= 3) {
                      Swal.fire({
                          title: 'Maximum Reached',
                          text: 'You can only add up to 3 past employments.',
                          icon: 'warning',
                          confirmButtonText: 'OK'
                      });
                      return false;
                  }

                  pastEmploymentCount++;
                  const newEntry = document.createElement('div');
                  newEntry.className = 'past-employment-entry mb-4 border-bottom pb-4';
                  newEntry.innerHTML = `
                    <input type="hidden" name="employed_as[]" value="Experienced">
                    <div class="experience-fields">


                        <div class="row">
                             <div class="col-xxl-4">
                                <div class="form-group ">
                                    <label for="past_organisation_name_${pastEmploymentCount}"  class="form-label">Organisation Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="past_organisation_name_${pastEmploymentCount}"
                                           name="past_organisation_name[]" placeholder="Enter Organisation Name" >
                                </div>
                            </div>


                               <div class="col-xxl-4">
                                <div class="form-group ">
                                    <label for="past_department_${pastEmploymentCount}"  class="form-label">Department <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="past_department_${pastEmploymentCount}"
                                           name="past_department[]" placeholder="Enter Department" >
                                </div>
                            </div>
                          <div class="col-xxl-4">
                                <div class="form-group">
                                    <label for="past_designation_${pastEmploymentCount}"  class="form-label">Designation <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="past_designation_${pastEmploymentCount}"
                                           name="past_designation[]" placeholder="Enter Designation" >
                                </div>
                            </div>
                        </div>





                        <div class="row">


                             <div class="col-xxl-4">
                                <div class="form-group">
                                    <label for="past_role_${pastEmploymentCount}"  class="form-label">Role <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="past_role_${pastEmploymentCount}"
                                           name="past_role[]" placeholder="Enter Role" >
                                </div>
                            </div>


                             <div class="col-xxl-4">
                                <div class="form-group">
                                    <label for="past_location_${pastEmploymentCount}"  class="form-label">Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="past_location_${pastEmploymentCount}"
                                           name="past_location[]" placeholder="Enter Location" >
                                </div>
                            </div>


                            <div class="col-xxl-4">
                                <div class="form-group ">
                                    <label for="past_annual_ctc_${pastEmploymentCount}"  class="form-label">Annual CTC (₹) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control past-annual-ctc"
                                           name="past_annual_ctc[]"  id="past_annual_ctc_${pastEmploymentCount}"  placeholder="Enter Annual CTC" >
                                </div>
                            </div>
                        </div>




                        <div class="row ">
                           <div class="col-xxl-4">
                                <div class="form-group">
                                    <label for="past_from_date_${pastEmploymentCount}"  class="form-label">Period of Service From <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="past_from_date_${pastEmploymentCount}"
                                           name="past_from_date[]" >
                                </div>
                            </div>
                       <div class="col-xxl-4">
                                <div class="form-group">
                                    <label for="past_to_date_${pastEmploymentCount}"  class="form-label">To <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="past_to_date_${pastEmploymentCount}"
                                           name="past_to_date[]" >
                                </div>
                            </div>

<div class="col-xxl-4">

 <div class="form-group">
                <label for="has_uan_${pastEmploymentCount}"  class="form-label">Do you have UAN (PF) number  <span class="text-danger">*</span></label>
                <select class="form-select" id="has_uan_${pastEmploymentCount}" name="has_uan[]" >
                    <option value="">Select Option</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div></div>

                        </div>





            <div class="form-group  uan-number-field" style="display: none;">
                <label for="uan_number_${pastEmploymentCount}"  class="form-label">UAN Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control uan-input" id="uan_number_${pastEmploymentCount}"
                       name="uan_number[]" placeholder="Enter 12-digit UAN Number"
                       maxlength="12" title="Enter a valid 12-digit UAN number">
                <div class="invalid-feedback">
                    Please enter a valid 12-digit UAN number.
                </div>
            </div>








                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-employment">
                            <i class="bi bi-trash me-1"></i>Remove
                        </button>
                    </div>
                `;

                  document.getElementById('pastEmploymentContainer').appendChild(newEntry);
                  const newSelect = newEntry.querySelector('select[name="has_uan[]"]');
                  if (newSelect) {
                      newSelect.addEventListener('change', function() {
                          handleUANChange(this);
                      });
                  }

                  document.querySelectorAll('.remove-employment').forEach(btn => {
                      btn.style.display = 'block';
                  });

                  newEntry.querySelector('.remove-employment').addEventListener('click',
                      function() {
                          this.closest('.past-employment-entry').remove();
                          pastEmploymentCount--;

                          if (document.querySelectorAll('.past-employment-entry')
                              .length <=
                              1) {
                              document.querySelectorAll('.remove-employment').forEach(
                                  btn => {
                                      btn.style.display = 'none';
                                  });
                          }
                      });

                  initializeCTCFields();
              });

              document.addEventListener('click', function(e) {
                  if (e.target && e.target.classList.contains('remove-employment')) {
                      e.target.closest('.past-employment-entry').remove();
                      pastEmploymentCount--;

                      if (document.querySelectorAll('.past-employment-entry').length <= 1) {
                          document.querySelectorAll('.remove-employment').forEach(btn => {
                              btn.style.display = 'none';
                          });
                      }
                  }
              });

              // Update the formatCTC function to handle decimals properly
              function formatCTC(e) {
                  const input = e.target;
                  let value = input.value.replace(/[^\d.]/g, '');

                  // Remove existing formatting if present
                  value = value.replace(/₹|,/g, '');

                  if (value === '') {
                      input.value = '';
                      return;
                  }

                  // Handle decimal points
                  if ((value.match(/\./g) || []).length > 1) {
                      value = value.substring(0, value.lastIndexOf('.'));
                  }

                  // Format with Indian numbering system
                  let parts = value.split('.');
                  let integerPart = parts[0];
                  let decimalPart = parts.length > 1 ? '.' + parts[1].substring(0, 2) : '';

                  // Format integer part with commas
                  integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

                  input.value = '₹' + integerPart + decimalPart;

                  // Maintain cursor position
                  const cursorPos = input.selectionStart;
                  const originalLength = input.value.length;
                  input.setSelectionRange(cursorPos, cursorPos);
              }

              // Update the initialization for all past-annual-ctc fields
              function initializeCTCFields() {
                  document.querySelectorAll('.past-annual-ctc').forEach(input => {
                      // Remove any existing event listeners to avoid duplicates
                      input.removeEventListener('input', formatCTC);
                      input.removeEventListener('blur', formatCTC);

                      // Add new event listeners
                      input.addEventListener('input', formatCTC);
                      input.addEventListener('blur', formatCTC);

                      // Format any existing value
                      if (input.value) {
                          const event = {
                              target: input
                          };
                          formatCTC(event);
                      }
                  });
              }




              initializeEntries();

              let referenceCount = $('.reference-entry').length;
              $('#addReference').click(function() {
                  if (referenceCount < 3) {
                      referenceCount++;




                   const newEntry = `<div class="reference-entry "><hr>
    <div class="row ">
        <div class="col-xxl-4">
            <div class="form-group">
                <label for="ref_name_${referenceCount}" class="form-label">Reference Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="ref_name_${referenceCount}" name="ref_name[]" placeholder="Enter name" required>
            </div>
        </div>
        <div class="col-xxl-4">
            <div class="form-group">
                <label for="ref_organization_name_${referenceCount}" class="form-label">Organization Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="ref_organization_name_${referenceCount}" name="ref_organization_name[]" placeholder="Enter organization name" required>
            </div>
        </div>
        <div class="col-xxl-4">
            <div class="form-group">
                <label for="ref_designation_${referenceCount}" class="form-label">Designation <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="ref_designation_${referenceCount}" name="ref_designation[]" placeholder="Enter designation" required>
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="col-xxl-4">
            <div class="form-group">
                <label for="mobile_no_${referenceCount}" class="form-label">Mobile No <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="mobile_no_${referenceCount}" name="mobile_no[]" placeholder="Enter mobile number" required>
            </div>
        </div>
        <div class="col-xxl-4">
            <div class="form-group">
                <label for="email_ref_${referenceCount}" class="form-label">Email</label>
                <input type="email" class="form-control email-input" id="email_ref_${referenceCount}" name="email_ref[]" placeholder="Enter email">
                <small class="text-danger error-message" style="display:none;">Invalid email format</small>
            </div>
        </div>
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-outline-danger btn-sm remove-reference">
            <i class="bi bi-trash me-1"></i>Remove
        </button>
    </div>
</div>`;





                      $('#referencesContainer').append(newEntry);
                      $('.remove-reference').show();

                      const newPhoneInput = document.getElementById(
                          `mobile_no_${referenceCount}`);
                      if (newPhoneInput) {
                          initializePhoneInput(newPhoneInput);
                      }
                  } else {
                      Swal.fire({
                          title: 'Maximum Reached',
                          text: 'You can only add up to 3 references.',
                          icon: 'warning',
                          confirmButtonText: 'OK'
                      });
                  }
              });

              $(document).on('click', '.remove-reference', function() {
                  $(this).closest('.reference-entry').remove();
                  referenceCount--;
                  if ($('.reference-entry').length === 1) {
                      $('.remove-reference').hide();
                  }
              });

              let familyCount = $('.family-entry').length;
              $('#addFamily').click(function() {
                  if (familyCount < 5) {
                      familyCount++;
                      const newEntry = `<div class="family-entry "><div class="row ">
                        <div class="col-lg-4"><div class="form-group"><label for="fa_name_${familyCount}" class="form-label">Name</label>
                        <input type="text" class="form-control" id="fa_name_${familyCount}" name="fa_name[]"></div></div>






    <div class="col-lg-4">
            <div class="form-group ">
                <label for="fa_name_${familyCount}" class="form-label">Relation</label>
                <input type="text" class="form-control relation-input" id="fa_relation_${familyCount}"
                    name="fa_relation_name[]" placeholder="Search relation ..."
                    >
                <input type="hidden" id="fa_relation_id_${familyCount}" name="fa_relation[]">
            </div>
            <ul id="fa_relation_${familyCount}_list" class="list-group"  style=" position: absolute;

            border: 2px solid #2b2929;
            max-height: 200px;
            overflow-y: auto;
            color: #ffff;
            width: 30%;
            z-index: 10;
            display: none;"></ul>
        </div>



                        <div class="col-lg-4"><div class="form-group"><label for="fa_occupation_${familyCount}" class="form-label">Occupation</label>
                        <input type="text" class="form-control" id="fa_occupation_${familyCount}" name="fa_occupation[]"></div></div>
                        </div><div class="text-end"><button type="button" class="btn btn-outline-danger btn-sm remove-family">
                        <i class="bi bi-trash me-1"></i>Remove</button></div></div>`;
                      $('#familyContainer').append(newEntry);
                      $('.remove-family').show();
                  } else {
                      Swal.fire({
                          title: 'Maximum Reached',
                          text: 'You can only add up to 5 family members.',
                          icon: 'warning',
                          confirmButtonText: 'OK'
                      });
                  }
              });

              $(document).on('click', '.remove-family', function() {
                  $(this).closest('.family-entry').remove();
                  familyCount--;
                  if ($('.family-entry').length === 1) {
                      $('.remove-family').hide();
                  }
              });

              let educationCount = $('.education-entry').length;
              $('#addEducation').click(function() {
                  if (educationCount < 5) {
                      educationCount++;
                      const newEntry = `<div class="education-entry ">

                        <div class="row ">


       <div class="col-xxl-4">
           <div class="form-group">
                        <label for="qualification_${educationCount}" class="form-label">Qualification<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control qualification-input"
                                   id="qualification_${educationCount}"
                                   name="qualification_name[]"
                                   placeholder="Search qualification ..."   >
                                     <input type="hidden" id="qualification_id_${educationCount}" name="qualification[]">
                            <button type="button" class="btn btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addQualificationModal">
                                Add
                            </button>
                        </div>
                    <ul id="qualification_${educationCount}_list" class="list-group"  style=" position: absolute;

            border: 2px solid #2b2929;
            max-height: 200px;
            overflow-y: auto;
            color: #ffff;
            width: 30%;
            z-index: 10;
            display: none;"></ul>
                    </div>
        </div>




   <div class="col-xxl-4">
                                <div class="form-group">
                                    <label for="name_of_institution_${educationCount}" class="form-label">Name Of Institution <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name_of_institution_${educationCount}" name="name_of_institution[]" placeholder="School/College/University" required>
                                </div>
                            </div>
                              <div class="col-xxl-4">
                                <div class="form-group">
                                    <label for="edu_location_${educationCount}" class="form-label">Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edu_location_${educationCount}" name="edu_location[]" placeholder="Enter location" required>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5 class="mb-3 mt-3">Period Of Study</h5>
                        <div class="row ">
                            <div class="col-xxl-4">
                                <div class="form-group">
                                    <label for="edu_from_date_${educationCount}" class="form-label">From<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="edu_from_date_${educationCount}" name="edu_from_date[]" required>
                                </div>
                            </div>
                             <div class="col-xxl-4">
                                <div class="form-group">
                                    <label for="edu_to_date_${educationCount}" class="form-label">To</label>
                                    <input type="date" class="form-control" id="edu_to_date_${educationCount}" name="edu_to_date[]">
                                </div>
                            </div>
                             <div class="col-xxl-4">
                                <div class="form-group">
                                    <label for="specialization_${educationCount}" class="form-label">Specialization <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="specialization_${educationCount}" name="specialization[]" required>
                                </div>
                            </div>
                        </div>
                        <div class="row ">

                         <div class="col-xxl-4">
    <div class="form-group">
        <label for="percentage_grade_${educationCount}" class="form-label">
            Percentage <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <input type="text"
                   class="form-control percentage-input"
                   id="percentage_grade_${educationCount}"
                   name="percentage_grade[]"
                   placeholder="Enter Percentage"
                   required
                   pattern="^(100(\\.00?)?|[0-9]{1,2}(\\.\\d{1,2})?)$">
            <span class="input-group-text">%</span>
        </div>
    </div>
</div>

                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-education">
                                <i class="bi bi-trash me-1"></i>Remove</button>
                        </div>
                    </div>`;
                      $('#educationContainer').append(newEntry);
                      $('.remove-education').show();

                      initPercentageInputs();
                      bindSearch(
                          `#qualification_${educationCount}`,
                          `#qualification-list_${educationCount}`,
                          "{{ route('qualification.search') }}",
                          "qualification-item",
                          "qua_id",
                          "qua_name"
                      );
                  } else {
                      Swal.fire({
                          title: 'Maximum Reached',
                          text: 'You can only add up to 5 education entries.',
                          icon: 'warning',
                          confirmButtonText: 'OK'
                      });
                  }
              });



              $(document).on('click', '.remove-education', function() {
                  $(this).closest('.education-entry').remove();
                  educationCount--;
                  if ($('.education-entry').length === 1) {
                      $('.remove-education').hide();
                  }
              });

              // Store timeout IDs to clear them later
              let eduDateTimeout, pastEmpTimeout, proEmpTimeout;

              $(document).on('blur', 'input[name="edu_to_date[]"]', function() {
                  validateEducationDate($(this).closest('.education-entry'));
              });

              $(document).on('blur', 'input[name="past_to_date[]"]', function() {
                  validateEmploymentDate($(this).closest('.past-employment-entry'));
              });

              $(document).on('blur', 'input[name="provision_to_date"]', function() {
                  validateproEmploymentDate($(this).closest('.past-employment-entry'));
              });

              function validateEducationDate(container) {
                  const fromInput = container.find('input[name="edu_from_date[]"]');
                  const toInput = container.find('input[name="edu_to_date[]"]');

                  clearTimeout(eduDateTimeout);
                  resetValidationStyles(fromInput);
                  resetValidationStyles(toInput);

                  // Validate FROM date
                  if (fromInput.val() && !isValidDateFormat(fromInput.val())) {
                      showDateError(fromInput, 'Invalid Date Format',
                          'Please enter date in YYYY-MM-DD format');
                      fromInput.val('');
                      return;
                  }

                  // Validate TO date
                  if (toInput.val() && !isValidDateFormat(toInput.val())) {
                      showDateError(toInput, 'Invalid Date Format',
                          'Please enter date in YYYY-MM-DD format');
                      toInput.val('');
                      return;
                  }

                  if (shouldValidateDates(fromInput, toInput)) {
                      const fromDate = new Date(fromInput.val());
                      const toDate = new Date(toInput.val());

                      if (toDate < fromDate) {
                          eduDateTimeout = setTimeout(() => {
                              showDateError(
                                  toInput,
                                  'Invalid Education Date',
                                  'End date cannot be before start date'
                              );
                              toInput.val('');
                          }, 300);
                      }
                  }
              }

              function validateEmploymentDate(container) {
                  const fromInput = container.find('input[name="past_from_date[]"]');
                  const toInput = container.find('input[name="past_to_date[]"]');

                  clearTimeout(pastEmpTimeout);
                  resetValidationStyles(fromInput);
                  resetValidationStyles(toInput);

                  // Validate FROM date
                  if (fromInput.val() && !isValidDateFormat(fromInput.val())) {
                      showDateError(fromInput, 'Invalid Date Format',
                          'Please enter date in YYYY-MM-DD format');
                      fromInput.val('');
                      return;
                  }

                  // Validate TO date
                  if (toInput.val() && !isValidDateFormat(toInput.val())) {
                      showDateError(toInput, 'Invalid Date Format',
                          'Please enter date in YYYY-MM-DD format');
                      toInput.val('');
                      return;
                  }

                  if (shouldValidateDates(fromInput, toInput)) {
                      const fromDate = new Date(fromInput.val());
                      const toDate = new Date(toInput.val());

                      if (toDate < fromDate) {
                          pastEmpTimeout = setTimeout(() => {
                              showDateError(
                                  toInput,
                                  'Invalid Employment Date',
                                  'End date cannot be before start date'
                              );
                              toInput.val('');
                          }, 300);
                      }
                  }
              }

              function validateproEmploymentDate(container) {
                  const fromInput = container.find('input[name="provision_from_date"]');
                  const toInput = container.find('input[name="provision_to_date"]');

                  clearTimeout(proEmpTimeout);
                  resetValidationStyles(fromInput);
                  resetValidationStyles(toInput);

                  // Validate FROM date
                  if (fromInput.val() && !isValidDateFormat(fromInput.val())) {
                      showDateError(fromInput, 'Invalid Date Format',
                          'Please enter date in YYYY-MM-DD format');
                      fromInput.val('');
                      return;
                  }

                  // Validate TO date
                  if (toInput.val() && !isValidDateFormat(toInput.val())) {
                      showDateError(toInput, 'Invalid Date Format',
                          'Please enter date in YYYY-MM-DD format');
                      toInput.val('');
                      return;
                  }

                  if (shouldValidateDates(fromInput, toInput)) {
                      const fromDate = new Date(fromInput.val());
                      const toDate = new Date(toInput.val());

                      if (toDate < fromDate) {
                          proEmpTimeout = setTimeout(() => {
                              showDateError(
                                  toInput,
                                  'Invalid Employment Date',
                                  'End date cannot be before start date'
                              );
                              toInput.val('');
                          }, 300);
                      }
                  }
              }

              function isValidDateFormat(dateString) {
                  if (!dateString) return false;

                  // Regular expression to match YYYY-MM-DD format
                  const regex = /^\d{4}-\d{2}-\d{2}$/;
                  if (!regex.test(dateString)) return false;

                  // Check if it's a valid date
                  const parts = dateString.split('-');
                  const year = parseInt(parts[0], 10);
                  const month = parseInt(parts[1], 10);
                  const day = parseInt(parts[2], 10);

                  // Check the ranges of month and day
                  if (year < 1000 || year > 3000 || month === 0 || month > 12) return false;

                  const monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

                  // Adjust for leap years
                  if (year % 400 === 0 || (year % 100 !== 0 && year % 4 === 0)) {
                      monthLength[1] = 29;
                  }

                  return day > 0 && day <= monthLength[month - 1];
              }

              function shouldValidateDates(fromInput, toInput) {
                  return fromInput.val() && toInput.val();
              }

              function resetValidationStyles(input) {
                  input.removeClass('is-invalid is-valid');
              }

              function showDateError(input, title, message) {
                  Swal.fire({
                      icon: 'error',
                      title: title,
                      text: message,
                      confirmButtonColor: '#3085d6'
                  });
                  input.addClass('is-invalid').focus();
              }

              // Allow manual typing but enforce proper format
              $('input[type="date"]').on('keydown', function(e) {
                  // Allow: backspace, delete, tab, escape, enter, arrows
                  if ([46, 8, 9, 27, 13, 37, 38, 39, 40].includes(e.keyCode)) {
                      return;
                  }

                  // Allow: Ctrl+A, Ctrl+C, Ctrl+X
                  if ((e.keyCode === 65 || e.keyCode === 67 || e.keyCode === 88) && e
                      .ctrlKey ===
                      true) {
                      return;
                  }

                  // Allow: numbers (0-9) and dash (-)
                  if ((e.keyCode >= 48 && e.keyCode <= 57) || e.keyCode === 189 || e
                      .keyCode ===
                      109) {
                      // Check if we're at the right position for dashes (after 4 and 7 characters)
                      const currentPos = this.selectionStart;
                      const currentValue = this.value;

                      // Positions where dashes should be (after 4th and 7th characters)
                      if ((currentPos === 4 || currentPos === 7) && e.keyCode !== 189 && e
                          .keyCode !== 109) {
                          this.value = currentValue.substring(0, currentPos) + '-' +
                              currentValue
                              .substring(currentPos);
                          this.setSelectionRange(currentPos + 1, currentPos + 1);
                      }
                      return;
                  }

                  // Prevent all other keys
                  e.preventDefault();
              });

              // Auto-format date input while typing
              $('input[type="date"]').on('input', function() {
                  const value = this.value.replace(/\D/g, '');

                  if (value.length > 4) {
                      this.value = value.substring(0, 4) + '-' + value.substring(4, 6) + '-' +
                          value.substring(6, 8);
                  } else if (value.length > 2) {
                      this.value = value.substring(0, 4) + '-' + value.substring(4, 6);
                  }
              });

              // Image cropper functionality




              // Image cropper functionality
              const $modal = $('#cropperModal');
              const cropperImage = document.getElementById('cropper_image');
              const imageInput = document.getElementById('employee_image');
              const imageError = document.getElementById('image_error');
              const form = document.querySelector('form');
              let cropper;

              function showError(message) {
                  imageError.textContent = message;
                  imageError.style.display = 'block';
                  imageInput.classList.add('is-invalid');
              }

              function clearError() {
                  imageError.textContent = '';
                  imageError.style.display = 'none';
                  imageInput.classList.remove('is-invalid');
              }

              $('#employee_image').change(function(event) {
                  const files = event.target.files;
                  $('#remove_image').val('0');

                  if (!files || files.length === 0) {
                      showError('Please select an image.');
                      return;
                  }

                  const file = files[0];
                  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

                  if (!allowedTypes.includes(file.type)) {
                      showError('Only JPG, JPEG, and PNG formats are allowed.');
                      return;
                  }

                  clearError();

                  const reader = new FileReader();
                  reader.onload = function(event) {
                      cropperImage.src = event.target.result;
                      $modal.modal('show');
                  };
                  reader.readAsDataURL(file);
              });

              $modal.on('shown.bs.modal', function() {
                  cropper = new Cropper(cropperImage, {
                      aspectRatio: 1,
                      viewMode: 1,
                      autoCropArea: 0.8,
                      preview: '.preview',
                      dragMode: 'move',
                      cropBoxResizable: false,
                      cropBoxMovable: true,
                  });

                  $('#zoom_in').click(function() {
                      if (cropper) cropper.zoom(0.1);
                  });

                  $('#zoom_out').click(function() {
                      if (cropper) cropper.zoom(-0.1);
                  });
              });

              $('#crop_image').click(function() {
                  if (cropper) {
                      const canvas = cropper.getCroppedCanvas({
                          width: 500,
                          height: 500,
                          fillColor: '#fff',
                          imageSmoothingEnabled: true,
                          imageSmoothingQuality: 'high'
                      });

                      if (canvas) {
                          const croppedImage = canvas.toDataURL('image/jpeg');
                          $('#cropped_image_data').val(croppedImage);
                          $modal.modal('hide');

                          // Show preview of cropped image
                          $('.image-upload-container').find('img').remove();
                          $('.image-upload-container').append(`
                            <div class="mt-2">
                                <img src="${croppedImage}" alt="Cropped Image" width="100" class="img-thumbnail">

                            </div>
                        `);
                      }
                  }
              });

              $modal.on('hidden.bs.modal', function() {
                  if (cropper) {
                      cropper.destroy();
                      cropper = null;
                  }
                  imageInput.value = '';
              });

              // Remove image button
              $(document).on('click', '.remove-image', function() {
                  $('#remove_image').val('1');
                  $(this).closest('.image-upload-container').find('img').remove();
                  $(this).remove();
                  $('#cropped_image_data').val('');
              });

              // Form validation on submit
              form.addEventListener('submit', function(e) {
                  let isValid = true;

                  // Validate all required fields
                  $('input[required], select[required], textarea[required]').each(function() {
                      if (!$(this).val()) {
                          $(this).addClass('is-invalid');
                          if (!$(this).next('.invalid-feedback').length) {
                              $(this).after(
                                  '<div class="invalid-feedback text-danger small mt-1">This field is required</div>'
                              );
                          }
                          isValid = false;
                      }
                  });

                  if (!isValid) {
                      e.preventDefault();
                      scrollToInvalid();
                      Swal.fire({
                          title: 'Validation Error',
                          text: 'Please fill all required fields',
                          icon: 'error',
                          confirmButtonText: 'OK'
                      });
                  }
              });

              initializeEntries();
          });
      });
  </script>


  <style>
      :root {



          --medium-gray: #e9ecef;
          --dark-gray: #6c757d;
          --success-color: #4bb543;

          --border-radius: 8px;
          --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
          --transition: all 0.3s ease;
      }

      /* Base Styles */
      #msform {
          width: 100%;
          max-width: 1900px;
          margin: 40px auto;
          position: relative;
          color: var(--text-color);
          display: flex;
          gap: 30px;
      }

      /* Dual Progress Container */
      .dual-progress-container {
          width: 250px;

      }

      /* CSS */
      /* Dual Progress Container */
      .dual-progress-container {
          width: 250px;
      }

      /* Vertical Progress Bar for Desktop (730px+) */
      .vertical-progress {
          display: none;
          flex-direction: column;
          gap: 70px;
          background: rgba(36, 35, 35, 0.068);
          padding: 30px;
          border-radius: var(--border-radius);
          box-shadow: var(--box-shadow);
          margin-bottom: 0;
          overflow: hidden;
          counter-reset: step;
          padding-left: 0;
          padding: 50px;
      }

      .vertical-progress li {
          list-style-type: none;
          color: var(--dark-gray);
          font-size: 13px;
          font-weight: 600;
          position: relative;
          text-align: left;
          padding-left: 50px;
          min-height: 40px;
          display: flex;
          align-items: center;
      }

      .vertical-progress li:before {
          content: "";
          width: 36px;
          height: 36px;
          line-height: 36px;
          font-size: 14px;
          color: #2c2b2b;
          background: var(--medium-gray);
          border-radius: 50%;
          border: .5px solid #00000017;
          position: absolute;
          left: 2px;
          top: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          transform: translateY(-50%);
          z-index: 2;
          transition: var(--transition);
      }

      /* Step-specific icons */
      .vertical-progress li:nth-child(1):before {
          content: "\F4D6";
          /* Bootstrap person icon */
          font-family: "bootstrap-icons";
      }

      .vertical-progress li:nth-child(2):before {
          content: "\F4D0";
          /* Bootstrap people icon */
          font-family: "bootstrap-icons";
      }

      .vertical-progress li:nth-child(3):before {
          content: "\F6FE";
          /* Bootstrap book icon */
          font-family: "bootstrap-icons";
      }

      .vertical-progress li:nth-child(4):before {
          content: "\F1CC";
          /* Bootstrap briefcase icon */
          font-family: "bootstrap-icons";
      }

      .vertical-progress li:nth-child(5):before {
          content: "\F28B";
          /* Bootstrap building icon */
          font-family: "bootstrap-icons";
      }

      .vertical-progress li:nth-child(6):before {
          content: "\F4D6";
          /* Bootstrap person-lines-fill icon */
          font-family: "bootstrap-icons";
      }

      .vertical-progress li:after {
          content: '';
          width: 2px;
          height: 7rem;
          background: var(--medium-gray);
          position: absolute;
          border: .5px solid #0000000e;
          left: 18px;
          top: 50%;
          z-index: 1;
          transition: var(--transition);
      }

      .vertical-progress li:last-child:after {
          display: none;
      }

      .vertical-progress li.active:before,
      .vertical-progress li.active:after {
          background: var(--ra-primary-set);
          color: #fff
      }

      /* success color */
      .vertical-progress li:nth-child(1).completed::before {
          content: "\F4D6";
          /* Bootstrap person icon */
          background: var(--success-color);
          font-family: "bootstrap-icons";
          color: #fff
      }

      .vertical-progress li:nth-child(2).completed::before {
          content: "\F4D0";
          background: var(--success-color);
          /* Bootstrap people icon */
          font-family: "bootstrap-icons";
          color: #fff
      }

      .vertical-progress li:nth-child(3).completed::before {
          content: "\F6FE";
          background: var(--success-color);
          /* Bootstrap book icon */
          font-family: "bootstrap-icons";
          color: #fff
      }

      .vertical-progress li:nth-child(4).completed::before {
          content: "\F1CC";
          background: var(--success-color);
          /* Bootstrap briefcase icon */
          font-family: "bootstrap-icons";
          color: #fff
      }

      .vertical-progress li:nth-child(5).completed::before {
          content: "\F28B";
          background: var(--success-color);
          /* Bootstrap building icon */
          font-family: "bootstrap-icons";
          color: #fff
      }

      .vertical-progress li:nth-child(6).completed::before {
          content: "\F4D6";
          background: var(--success-color);
          /* Bootstrap person-lines-fill icon */
          font-family: "bootstrap-icons";
          color: #fff
      }

/* Disable HTML5 validation bubbles */
:invalid {
    box-shadow: none !important;
}

input:invalid, textarea:invalid, select:invalid {
    box-shadow: none !important;
}

/* Remove Firefox red glow */
input:-moz-ui-invalid,
select:-moz-ui-invalid,
textarea:-moz-ui-invalid {
    box-shadow: none !important;
}

/* Remove Chrome validation styling */
input:focus:invalid,
textarea:focus:invalid,
select:focus:invalid {
    box-shadow: none !important;
}

/* Red border for invalid fields */
.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
}

/* Style for bloodgroup field to indicate it's selection-only */
#bloodgroup {
    cursor: pointer;
    background-color: #f8f9fa;
}

#bloodgroup:focus {
    background-color: #fff;
}

      .vertical-progress li.completed:after {
          background: var(--success-color);
          color: #fff;
      }

      /* Horizontal Progress Bar for Mobile */
      #progressbar {
          width: 100%;
          margin-bottom: 0;
          overflow: hidden;
          counter-reset: step;
          padding-left: 0;
          display: flex;
          flex-direction: row;
          flex-wrap: wrap;
          justify-content: center;
          gap: 15px;
          background: rgba(36, 35, 35, 0.068);
          padding: 20px;
          border-radius: var(--border-radius);
          box-shadow: var(--box-shadow);
      }

      #progressbar li {
          list-style-type: none;
          color: var(--dark-gray);
          font-size: 10px;
          font-weight: 600;
          position: relative;
          text-align: center;
          padding-top: 40px;
          min-width: 80px;
      }

      #progressbar li:before {
          content: "";
          width: 30px;
          height: 30px;
          line-height: 30px;
          display: block;
          font-size: 12px;
          color: #252424;
          border: .5px solid #00000017;
          background: var(--medium-gray);
          border-radius: 50%;
          margin: 0 auto 10px;
          position: absolute;
          top: 0;
          left: 50%;
          transform: translateX(-50%);
          display: flex;
          align-items: center;
          justify-content: center;
          font-family: "bootstrap-icons";
      }

      /* Step-specific icons for mobile */
      #progressbar li:nth-child(1):before {
          content: "\F4D6";
          /* Bootstrap person icon */
      }

      #progressbar li:nth-child(2):before {
          content: "\F4D0";
          /* Bootstrap people icon */
      }

      #progressbar li:nth-child(3):before {
          content: "\F6FE";
          /* Bootstrap book icon */
      }

      #progressbar li:nth-child(4):before {
          content: "\F1CC";
          /* Bootstrap briefcase icon */
      }

      #progressbar li:nth-child(5):before {
          content: "\F28B";
          /* Bootstrap building icon */
      }

      #progressbar li:nth-child(6):before {
          content: "\F4D6";
          /* Bootstrap person-lines-fill icon */
      }

      #progressbar li.active:before {
          background: var(--ra-primary-set);
          color: #fff
      }

      #progressbar li.completed:before {
          background: var(--success-color);
          content: "\f633";
          color: #fff
              /* Bootstrap check-circle icon */
      }





      #progressbar li:nth-child(1):before {
          content: "\F4D6";
          /* Bootstrap person icon */
      }

      #progressbar li:nth-child(2):before {
          content: "\F4D0";
          /* Bootstrap people icon */
      }

      #progressbar li:nth-child(3):before {
          content: "\F6FE";
          /* Bootstrap book icon */
      }

      #progressbar li:nth-child(4):before {
          content: "\F1CC";
          /* Bootstrap briefcase icon */
      }

      #progressbar li:nth-child(5):before {
          content: "\F28B";
          /* Bootstrap building icon */
      }

      #progressbar li:nth-child(6):before {
          content: "\F4D6";
          /* Bootstrap person-lines-fill icon */
      }












      .remove-image {
          position: absolute;
          top: 70px;
          left: 85px;
          border-radius: 50%;
          padding: 10px;

          border: 1px solid crimson;


      }














      .progress-label {
          display: block;
          text-align: center;
          line-height: 1.3;
      }

      /* Responsive Adjustments */
      @media (min-width: 730px) {
          .dual-progress-container {
              width: 250px;
          }

          .vertical-progress {
              display: flex;
          }

          #progressbar {
              display: none;
          }
      }

      @media (max-width: 729px) {
          #msform {
              flex-direction: column;
          }

          .dual-progress-container {
              width: 100%;
          }

          #progressbar {
              display: flex;
          }
      }

      @media (max-width: 480px) {
          #progressbar li {
              font-size: 10px;
              min-width: 60px;
              padding-top: 35px;
          }

          #progressbar li:before {
              width: 25px;
              height: 25px;
              line-height: 25px;
          }
      }

      /* Form Container */
      .form-container {
          flex: 1;
          background: white;
          border-radius: var(--border-radius);
          box-shadow: var(--box-shadow);
          padding: 30px;
      }

      /* Fieldset Styling */
      #msform fieldset {
          background: white;
          border: 0 none;
          border-radius: var(--border-radius);
          padding: 0;
          box-sizing: border-box;
          width: 100%;
          margin: 0;
          position: relative;
      }

      /* Hide all except first fieldset */
      #msform fieldset:not(:first-of-type) {
          display: none;
      }

      /* Card Styling */
      .card {
          border: none;
          border-radius: var(--border-radius);
          box-shadow: none;
          margin-bottom: 0;
      }

      .card-header {
          background-color: transparent;
          color: #000;
          border-bottom: none;
          border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
          padding: 18px 25px;
          font-weight: 600;
          font-size: 10px;
      }

      .card-header h4 {
          color: var(--ra-primary-set) !important;
          font-size: 25px;
          font-weight: 700;
          letter-spacing: .2em;
      }

      .card-body {
          padding: 25px;
      }

      /* Form Group Styling */
      .form-group {
          margin-bottom: 1.5rem;
          position: relative;
      }

      .form-label {
          display: block;
          margin-bottom: 8px;
          font-weight: 500;
          color: var(--text-color);
          font-size: 14px;
      }

      .form-control,
      .form-select {
          height: 45px;
          padding: 10px 15px;
          border: 1px solid var(--medium-gray);
          border-radius: var(--border-radius);
          background-color: white;
          font-size: 15px;
          transition: var(--transition);
          box-shadow: none;
      }

      .form-control:focus,
      .form-select:focus {
          border-color: var(--ra-primary-set);
          box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
          outline: none;
      }

      textarea.form-control {

          resize: vertical;
      }

      /* Radio & Checkbox Styling */
      .form-check {
          position: relative;
          display: inline-flex;
          align-items: center;
          margin-right: 15px;
      }

      .form-check-input {
          width: 18px;
          height: 18px;
          margin-right: 8px;
          margin-top: 0;
      }

      .form-check-label {
          font-size: 14px;
          user-select: none;
      }

      /* Button Styling */
      .btn {
          padding: 7px 20px;
          border-radius: var(--border-radius);
          font-weight: 500;
          font-size: 14px;
          transition: var(--transition);
          display: inline-flex;
          align-items: center;
          justify-content: center;
      }

      .btn-primary {
          background-color: var(--ra-primary-set);
          border-color: var(--ra-primary-set);
      }

      .btn-primary:hover {
          background-color: var(--ra-primary-set);
          border-color: var(--secondary-color);
          transform: translateY(-2px);
      }

      .btn-outline-primary {
          border: 1px solid var(--ra-primary-set);
          color: var(--ra-primary-set);
          background: transparent;
      }

      .btn-outline-primary:hover {
          background-color: var(--ra-primary-set);
          color: white;
      }

      .btn-sm {
          padding: 8px 16px;
          font-size: 13px;
      }

      /* Entry Sections */
      .family-entry,
      .education-entry,
      .past-employment-entry,
      .reference-entry {
          position: relative;
          padding: 20px;
          margin-bottom: 20px;
          border-radius: var(--border-radius);
          background-color: var(--light-gray);
          border: 1px solid var(--medium-gray);
      }

      /* Remove buttons */
      .remove-family,
      .remove-education,
      .remove-employment,
      .remove-reference {
          display: none;
      }

      /* Section Headers */
      h5 {
          font-size: 16px;
          font-weight: 600;
          color: var(--text-color);
          margin-bottom: 20px;
          position: relative;
          padding-bottom: 8px;
      }

      h5:after {
          content: '';
          position: absolute;
          left: 0;
          bottom: 0;
          width: 40px;
          height: 2px;
          background: var(--ra-primary-set);
      }


      img {
          display: block;
          max-width: 100%;
      }

      .preview {
          overflow: hidden;
          width: 170px;
          height: 170px;
          margin: 10px;
          border: 1px solid #1a70c7;
      }

      .modal-lg {
          max-width: 650px !important;
      }


      /* imp one  country code  input filed  width  adjust */
      .intl-tel-input {
          width: 100%;
      }

      .iti {
          width: 100%;
      }
  </style>

  <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div id="flash-message-container" class="mt-2"></div>
              <p class="text-danger mt-2 ms-3">Image size: 500 × 500 pixels</p>
              <div class="modal-header">
                  <h5 class="modal-title" id="cropperModalLabel">Crop and Save Image</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="img-container">
                      <div class="row">
                          <div class="col-md-8">
                              <img src="" id="cropper_image" class="img-fluid">
                          </div>
                          <div class="col-lg-4">
                              <div class="preview"></div>
                              <div class="btnbar mt-3 text-center">
                                  <button type="button" id="zoom_in" class="btn btn-sm btn-success">
                                      <i class="bi bi-plus"></i>
                                  </button>
                                  <button type="button" id="zoom_out" class="btn btn-sm btn-success ms-2">
                                      <i class="bi bi-dash"></i>
                                  </button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary" id="crop_image">Crop & Save</button>
              </div>
          </div>
      </div>
  </div>

  <style>
      #companyemail-list,
      #curlocation-list,
      #curdepartment-list,
      #bloodgroup-list,
      #jobtype-list,
      #fa_relation_0_list,
      #relationship-list,
      #currole-list,
      #curdesignation-list {
          position: absolute;

          border: 2px solid #2b2929;
          max-height: 200px;
          overflow-y: auto;
          color: #ffff;
          width: 100%;
          z-index: 10;
          display: none;
      }

      #companyemail-list,
      #curlocation-list,
      #curdepartment-list,
      #bloodgroup-list,
      #jobtype-list,
      #fa_relation_0_list,
      #relationship-list,
      #currole-list,
      #curdesignation-list li {
          cursor: pointer;
      }


      #companyemail-list,
      #curlocation-list,
      #curdepartment-list,
      #bloodgroup-list,
      #jobtype-list,
      #fa_relation_0_list,
      #relationship-list,
      #currole-list,
      #curdesignation-list li:hover {
          background-color: var(--ra-primary-set);
          color: #fff;

      }
  </style>

  <script>
      $(document).ready(function() {
         $('#bloodgroup').on('keydown', function(e) {
        // Allow: backspace, delete, tab, escape, enter, arrows
        if ([8, 46, 9, 27, 13, 37, 38, 39, 40].includes(e.keyCode)) {
            return;
        }
        // Allow: Ctrl+A, Ctrl+C, Ctrl+X, Ctrl+V
        if ((e.keyCode === 65 || e.keyCode === 67 || e.keyCode === 88 || e.keyCode === 86) && e.ctrlKey === true) {
            return;
        }
        // Allow navigation keys
        if (e.keyCode >= 33 && e.keyCode <= 40) {
            return;
        }
        // Prevent all other key inputs
        e.preventDefault();
    });

    // Show dropdown on click
    $('#bloodgroup').on('click focus', function() {
        let query = $(this).val().trim();
        // If empty, show all blood groups
        if (query === '') {
            query = ' ';
        }

        $.ajax({
            url: "{{ route('bloodgroup.search') }}",
            method: "GET",
            data: { search: query },
            success: function(response) {
                const $list = $('#bloodgroup-list').empty();

                if (response.length > 0) {
                    response.forEach(function(item) {
                        $list.append(
                            `<li class="list-group-item bloodgroup-item"
                                 data-id="${item.bloodgroup_id}"
                                 data-name="${item.bloodgroup_name}">
                                 ${item.bloodgroup_name}
                            </li>`
                        );
                    });
                    $list.show();
                } else {
                    $list.append(`<li class="list-group-item disabled">No blood groups found</li>`);
                    $list.show();
                }
            }
        });
    });

    // Handle bloodgroup selection
    $(document).on("click", "#bloodgroup-list .bloodgroup-item", function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#bloodgroup').val(name);
        $('#bloodgroup_id').val(id);
        $('#bloodgroup-list').empty().hide();
    });
          // Generic search function
          function bindSearch(inputSelector, listSelector, url, itemClass, idKey, nameKey, hiddenInputSelector =
              null) {
              $(inputSelector).on('input focus', function() {
                  let query = $(this).val().trim();

                  $.ajax({
                      url: url,
                      method: "GET",
                      data: {
                          search: query
                      },
                      success: function(response) {
                          const $list = $(listSelector).empty();

                          if (response.length > 0) {
                              response.forEach(function(item) {
                                  $list.append(
                                      `<li class="list-group-item ${itemClass}"
                                 data-id="${item[idKey]}"
                                 data-name="${item[nameKey]}">
                                 ${item[nameKey]}
                                </li>`
                                  );
                              });
                          } else {
                              $list.append(
                                  `<li class="list-group-item disabled">No results found</li>`
                              );
                          }
                          $list.show();
                      }
                  });
              });

              $(document).on("click", `${listSelector} .${itemClass}`, function() {
                  const id = $(this).data('id');
                  const name = $(this).data('name');
                  $(inputSelector).val(name);
                  if (hiddenInputSelector) {
                      $(hiddenInputSelector).val(id);
                  }
                  $(listSelector).empty().hide();
              });

              $(document).on("click", function(e) {
                  if (!$(e.target).closest(inputSelector).length &&
                      !$(e.target).closest(listSelector).length) {
                      $(listSelector).hide();
                  }
              });
          }

          // Initialize all search fields
          const searches = [{
                  input: "#qualification",
                  list: "#qualification-list",
                  url: "{{ route('qualification.search') }}",
                  itemClass: "qualification-item",
                  idKey: "qua_id",
                  nameKey: "qua_name"
              },

              {
                  input: "#cur_department",
                  list: "#curdepartment-list",
                  url: "{{ route('department.search') }}",
                  itemClass: "curdepartment-item",
                  idKey: "dep_id",
                  nameKey: "dep_name",
                  hidden: "#dep_id"
              },

              {
                  input: "#cur_designation",
                  list: "#curdesignation-list",
                  url: "{{ route('designation.search') }}",
                  itemClass: "curdesignation-item",
                  idKey: "des_id",
                  nameKey: "des_name",
                  hidden: "#des_id"
              },

              {
                  input: "#bloodgroup",
                  list: "#bloodgroup-list",
                  url: "{{ route('bloodgroup.search') }}",
                  itemClass: "bloodgroup-item",
                  idKey: "bloodgroup_id",
                  nameKey: "bloodgroup_name",
                  hidden: "#bloodgroup_id"
              },

              {
                  input: "#jobtype",
                  list: "#jobtype-list",
                  url: "{{ route('jobtype.search') }}",
                  itemClass: "jobtype-item",
                  idKey: "jobtype_id",
                  nameKey: "jobtype_name",
                  hidden: "#jobtype_id"
              },

              {
                  input: "#relationship",
                  list: "#relationship-list",
                  url: "{{ route('relationship.search') }}",
                  itemClass: "relationship-item",
                  idKey: "relationship_id",
                  nameKey: "relationship_name",
                  hidden: "#relationship_id"
              },

              {
                  input: "#email_company",
                  list: "#companyemail-list",
                  url: "{{ route('companyemail.search') }}",
                  itemClass: "companyemail-item",
                  idKey: "companyemail_id",
                  nameKey: "companyemail_name"
              },

              {
                  input: "#cur_location",
                  list: "#curlocation-list",
                  url: "{{ route('location.search') }}",
                  itemClass: "curlocation-item",
                  idKey: "branch_id",
                  nameKey: "branch_name",
                  hidden: "#branch_id"
              }
          ];

          searches.forEach(search => {
              bindSearch(
                  search.input,
                  search.list,
                  search.url,
                  search.itemClass,
                  search.idKey,
                  search.nameKey,
                  search.hidden
              );
          });

          // Dynamic relationship search for family members
          $(document).on('input focus', "input[id^='fa_relation_']", function() {
              const inputId = this.id;
              const listId = `${inputId}_list`;
              const search = $(this).val();

              if (search.length < 1) {
                  $(`#${listId}`).hide();
                  return;
              }

              $.ajax({
                  url: '{{ route('relationship.search') }}',
                  method: 'GET',
                  data: {
                      search
                  },
                  success: function(data) {
                      const $list = $(`#${listId}`).empty();

                      if (data.length > 0) {
                          data.forEach(function(relation) {
                              $list.append(`
                            <li class="list-group-item list-group-item-action relation-item" style="cursor:pointer;"
                                data-id="${relation.relationship_id}"
                                data-name="${relation.relationship_name}"
                                data-input="${inputId}">
                                ${relation.relationship_name}
                            </li>
                        `);
                          });
                          $list.show();
                      } else {
                          $list.hide();
                      }
                  }
              });
          });

          // Dynamic qualification search
          $(document).on('input focus', "input[id^='qualification_']", function() {
              const inputId = this.id;
              const listId = `${inputId}_list`;
              const search = $(this).val();

              if (search.length < 1) {
                  $(`#${listId}`).hide();
                  return;
              }

              $.ajax({
                  url: '{{ route('qualification.search') }}',
                  method: 'GET',
                  data: {
                      search
                  },
                  success: function(data) {
                      const $list = $(`#${listId}`).empty();

                      if (data.length > 0) {
                          data.forEach(function(qualification) {
                              $list.append(`
                            <li class="list-group-item list-group-item-action qualification-item" style="cursor:pointer;"
                                data-id="${qualification.qua_id}"
                                data-name="${qualification.qua_name}"
                                data-input="${inputId}">
                                ${qualification.qua_name}
                            </li>
                        `);
                          });
                          $list.show();
                      } else {
                          $list.hide();
                      }
                  }
              });
          });

          // Handle dynamic item selection
          $(document).on('click', '.relation-item, .qualification-item', function() {
              const inputId = $(this).data('input');
              const name = $(this).data('name');
              const id = $(this).data('id');

              $(`#${inputId}`).val(name);
              $(`#${inputId.replace(/(fa_relation_|qualification_)/, '$1id_')}`).val(id);
              $(`#${inputId}_list`).hide();
          });

          // Hide all lists when clicking outside
          $(document).on("click", function(e) {
              if (!$(e.target).closest("[id$='_list'], [id^='fa_relation_'], [id^='qualification_']")
                  .length) {
                  $("[id$='_list']").hide();
              }
          });
      });



      // Auto-fill hidden inputs if text value is present but hidden input is empty
      function autoFillHiddenInputs(pairs) {
          pairs.forEach(pair => {
              const nameInput = $(pair.nameInput);
              const hiddenInput = $(pair.hiddenInput);
              if (nameInput.val().trim() !== '' && hiddenInput.val().trim() === '') {
                  // Try to auto-match from initial list via AJAX
                  $.ajax({
                      url: pair.url,
                      method: 'GET',
                      data: {
                          search: nameInput.val().trim()
                      },
                      success: function(response) {
                          const match = response.find(item => item[pair.nameKey] === nameInput.val()
                              .trim());
                          if (match) {
                              hiddenInput.val(match[pair.idKey]);
                          }
                      }
                  });
              }
          });
      }

      autoFillHiddenInputs([{
              nameInput: '#bloodgroup',
              hiddenInput: '#bloodgroup_id',
              url: '{{ route('bloodgroup.search') }}',
              idKey: 'bloodgroup_id',
              nameKey: 'bloodgroup_name'
          },
          {
              nameInput: '#relationship',
              hiddenInput: '#relationship_id',
              url: '{{ route('relationship.search') }}',
              idKey: 'relationship_id',
              nameKey: 'relationship_name'
          },
          {
              nameInput: '#jobtype',
              hiddenInput: '#jobtype_id',
              url: '{{ route('jobtype.search') }}',
              idKey: 'jobtype_id',
              nameKey: 'jobtype_name'
          },
          {
              nameInput: '#cur_department',
              hiddenInput: '#dep_id',
              url: '{{ route('department.search') }}',
              idKey: 'dep_id',
              nameKey: 'dep_name'
          },
          {
              nameInput: '#cur_designation',
              hiddenInput: '#des_id',
              url: '{{ route('designation.search') }}',
              idKey: 'des_id',
              nameKey: 'des_name'
          },
          {
              nameInput: '#cur_location',
              hiddenInput: '#branch_id',
              url: '{{ route('location.search') }}',
              idKey: 'branch_id',
              nameKey: 'branch_name'
          },
      ]);
  </script>

  <script>
      let isValidPincode = false;

      document.getElementById('pincode').addEventListener('input', function() {
          const pincode = this.value.trim();

          if (/^\d{6}$/.test(pincode)) {
              fetchPincodeDetails(pincode);
          } else {
              clearAddressFields();
              isValidPincode = false;
          }
      });

      document.getElementById('flatno').addEventListener('focus', function() {
          const pincode = document.getElementById('pincode').value.trim();

          if (!isValidPincode) {
              Toastify({
                  text: "Invaild Pincode",
                  duration: 3000,
                  gravity: "top",
                  position: "right",
                  backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                  stopOnFocus: true
              }).showToast();

              document.getElementById('pincode').value = '';
              document.getElementById('flatno').value = '';
              clearAddressFields();
          }
      });

      function fetchPincodeDetails(pincode) {
          fetch(`https://api.postalpincode.in/pincode/${pincode}`)
              .then(response => response.json())
              .then(data => {
                  if (data[0].Status === 'Success' && data[0].PostOffice && data[0].PostOffice.length > 0) {
                      const firstOffice = data[0].PostOffice[0];
                      document.getElementById('country').value = firstOffice.Country || '';
                      document.getElementById('state').value = firstOffice.State || '';
                      document.getElementById('city').value = firstOffice.District || firstOffice.Name || '';
                      isValidPincode = true;
                  } else {
                      isValidPincode = false;

                      clearAddressFields();
                  }
              })
              .catch(error => {
                  isValidPincode = false;
                  console.error('Error fetching pincode details:', error);
                  clearAddressFields();
              });
      }

      function clearAddressFields() {
          document.getElementById('country').value = '';
          document.getElementById('state').value = '';
          document.getElementById('city').value = '';
      }

      document.addEventListener('DOMContentLoaded', function() {
          // Show remove buttons for existing entries
          if ($('.family-entry').length > 1) {
              $('.remove-family').show();
          }

          if ($('.education-entry').length > 1) {
              $('.remove-education').show();
          }

          if ($('.past-employment-entry').length > 1) {
              $('.remove-employment').show();
          }

          if ($('.reference-entry').length > 1) {
              $('.remove-reference').show();
          }
      });
  </script>
