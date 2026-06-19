{{-- This is a "partial" view: a small reusable piece of HTML
     included inside create.blade.php and edit.blade.php so we don't repeat the same form twice --}}

     @csrf

     @if(isset($car))
         {{-- when editing, Laravel forms only support GET/POST by default,
              so we add a hidden field to "fake" a PUT request --}}
         @method('PUT')
     @endif
     
     @if ($errors->any())
         <div class="alert alert-danger">
             <ul class="mb-0">
                 @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                 @endforeach
             </ul>
         </div>
     @endif
     
     <div class="row">
         <div class="col-md-6 mb-3">
             <label class="form-label">Marque</label>
             <input type="text" name="brand" class="form-control" value="{{ old('brand', $car->brand ?? '') }}" required>
         </div>
         <div class="col-md-6 mb-3">
             <label class="form-label">Modèle</label>
             <input type="text" name="model" class="form-control" value="{{ old('model', $car->model ?? '') }}" required>
         </div>
     </div>
     
     <div class="row">
         <div class="col-md-4 mb-3">
             <label class="form-label">Année</label>
             <input type="number" name="year" class="form-control" value="{{ old('year', $car->year ?? date('Y')) }}" required>
         </div>
         <div class="col-md-4 mb-3">
             <label class="form-label">Transmission</label>
             <select name="transmission" class="form-select" required>
                 <option value="manuelle" {{ old('transmission', $car->transmission ?? '') == 'manuelle' ? 'selected' : '' }}>Manuelle</option>
                 <option value="automatique" {{ old('transmission', $car->transmission ?? '') == 'automatique' ? 'selected' : '' }}>Automatique</option>
             </select>
         </div>
         <div class="col-md-4 mb-3">
             <label class="form-label">Carburant</label>
             <select name="fuel_type" class="form-select" required>
                 <option value="essence" {{ old('fuel_type', $car->fuel_type ?? '') == 'essence' ? 'selected' : '' }}>Essence</option>
                 <option value="diesel" {{ old('fuel_type', $car->fuel_type ?? '') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                 <option value="electrique" {{ old('fuel_type', $car->fuel_type ?? '') == 'electrique' ? 'selected' : '' }}>Électrique</option>
                 <option value="hybride" {{ old('fuel_type', $car->fuel_type ?? '') == 'hybride' ? 'selected' : '' }}>Hybride</option>
             </select>
         </div>
     </div>
     
     <div class="row">
         <div class="col-md-4 mb-3">
             <label class="form-label">Nombre de places</label>
             <input type="number" name="seats" class="form-control" value="{{ old('seats', $car->seats ?? 5) }}" required>
         </div>
         <div class="col-md-4 mb-3">
             <label class="form-label">Prix par jour (MAD)</label>
             <input type="number" step="0.01" name="price_per_day" class="form-control"
                    value="{{ old('price_per_day', $car->price_per_day ?? '') }}" required>
         </div>
         <div class="col-md-4 mb-3">
             <label class="form-label">Photo</label>
             <input type="file" name="image" class="form-control" accept="image/*">
             @if(isset($car) && $car->image)
                 <img src="{{ asset('storage/'.$car->image) }}" width="80" class="mt-2 rounded">
             @endif
         </div>
     </div>
     
     <div class="mb-3">
         <label class="form-label">Description</label>
         <textarea name="description" class="form-control" rows="3">{{ old('description', $car->description ?? '') }}</textarea>
     </div>
     
     {{-- this checkbox only makes sense when editing an existing car --}}
     @if(isset($car))
         <div class="form-check mb-3">
             <input type="checkbox" name="is_available" class="form-check-input" id="is_available"
                    {{ old('is_available', $car->is_available) ? 'checked' : '' }}>
             <label class="form-check-label" for="is_available">Disponible à la location</label>
         </div>
     @endif
     
     <button type="submit" class="btn btn-vehitor">
         {{ isset($car) ? 'Mettre à jour' : 'Ajouter la voiture' }}
     </button>
     