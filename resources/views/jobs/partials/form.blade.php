<div class="form-group">
    <label for="name">Job Name</label>
    <input type="text" class="form-control" name="name" value="{{ old('name', $job->name ?? '') }}" required>
</div>

<div class="form-group">
    <label for="body_corporate">Body Corporate</label>
    <input type="text" class="form-control" name="body_corporate" value="{{ old('body_corporate', $job->body_corporate ?? '') }}" required>
</div>

<!-- Add all other fields (plan, location, location_link, etc.) -->

<div class="form-group">
    <label for="base_price">Base Price</label>
    <input type="number" step="0.01" class="form-control" name="base_price" value="{{ old('base_price', $job->base_price ?? '') }}" required>
</div>

<h3>Job Scope</h3>

<!-- Job Scope Fields -->
<div class="form-check">
    <input type="checkbox" name="lawn_care" value="1" class="form-check-input" {{ old('lawn_care', $job->job_scope->lawn_care ?? false) ? 'checked' : '' }}>
    <label class="form-check-label">Lawn Care</label>
</div>

<!-- Add checkboxes for all other scope fields -->
