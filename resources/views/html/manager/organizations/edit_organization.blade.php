@extends('templates.manager_app')

@section('title', 'تعديل الجمعية')

@push('styles')
    <link rel="stylesheet" href="/assets/css/organizations/organizations.css">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <a href="{{ route('manager.organizations.index') }}" class="back-btn">
                <i class="fas fa-arrow-right"></i> العودة إلى قائمة الجمعيات
            </a>

            <div class="form-card">
                <h4 class="form-title">تعديل بيانات الجمعية</h4>

                <form method="POST" enctype="multipart/form-data"
                    action="{{ route('manager.organizations.update', $org->id ?? ($org['id'] ?? null)) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-building"></i>
                                <label class="form-label">اسم الجمعية</label>
                            </div>
                            <input type="text" name="name" class="form-control"
                                value="{{ $org->name ?? ($org['name'] ?? '') }}" required>
                        </div>

                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-tag"></i>
                                <label class="form-label">النوع</label>
                            </div>
                            <select name="type" class="form-control" required>
                                <option value="local"
                                    {{ ($org->type ?? ($org['type'] ?? '')) == 'local' ? 'selected' : '' }}>محلية
                                </option>
                                <option value="external"
                                    {{ ($org->type ?? ($org['type'] ?? '')) == 'external' ? 'selected' : '' }}>خارجية
                                </option>
                            </select>
                        </div>

                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-phone"></i>
                                <label class="form-label">هاتف الاتصال</label>
                            </div>
                            <input type="text" name="contact_phone" class="form-control"
                                value="{{ $org->contact_phone ?? ($org['contact_phone'] ?? '') }}">
                        </div>


                    </div>

                    <div class="form-row">

                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-globe"></i>
                                <label class="form-label">الموقع الإلكتروني</label>
                            </div>
                            <input type="url" name="website_url" class="form-control"
                                value="{{ $org->website_url ?? ($org['website_url'] ?? '') }}">
                        </div>

                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-envelope"></i>
                                <label class="form-label">بريد الاتصال</label>
                            </div>
                            <input type="email" name="contact_email" class="form-control"
                                value="{{ $org->contact_email ?? ($org['contact_email'] ?? '') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-align-right"></i>
                                <label class="form-label">الوصف</label>
                            </div>
                            <textarea name="description" class="form-control" rows="4">{{ $org->description ?? ($org['description'] ?? '') }}</textarea>
                        </div>


                        <div class="input-container">
                            <div class="input-group-label">
                                <i class="fas fa-image"></i>
                                <label class="form-label">شعار الجمعية</label>
                            </div>
                            <input type="file" name="logo" accept="image/*" class="form-control">
                            @if (!empty($org->logo ?? ($org['logo'] ?? '')))
                                <div class="mt-2">
                                    <img src="{{ asset('assets/images/organizations/' . ($org->logo ?? $org['logo'])) }}"
                                        class="activity-img-preview" />
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button class="btn btn-submit" type="submit">
                            <i class="fas fa-save"></i> حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/js/bootstrap.js"></script>
@endpush
