@extends('layouts.app')
@section('title', 'Contact Us — DAMS Medical Center')
@section('meta-description', 'Contact DAMS Medical Center. Address, phone numbers, and Google Maps location.')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <p>We're here to help — reach us anytime</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="contact-grid">
            <div>
                <div class="contact-card">
                    <h2>Get in Touch</h2>
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div><strong>Address</strong><p>123 Medical Road, Dhaka 1200, Bangladesh</p></div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div>
                            <strong>Phone</strong>
                            <p><a href="tel:+8801700000000">+880 1700-000000</a></p>
                            <p><a href="tel:+8801800000000">+880 1800-000000</a></p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">✉️</div>
                        <div><strong>Email</strong><p><a href="mailto:info@dams.com">info@dams.com</a></p></div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">🕐</div>
                        <div>
                            <strong>Chamber Hours</strong>
                            <p>Saturday – Thursday: 9:00 AM – 9:00 PM</p>
                            <p>Friday: 5:00 PM – 9:00 PM</p>
                            <p style="color:#dc2626;font-weight:600">🚑 Emergency: 24 Hours / 7 Days</p>
                        </div>
                    </div>
                    <a href="{{ route('appointment.form') }}" class="btn btn-primary btn-block mt-4">📅 Book Appointment</a>
                </div>
            </div>
            <div>
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.902!2d90.3998606!3d23.7468!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDQ0JzQ4LjUiTiA5MMKwMjMnNTkuNSJF!5e0!3m2!1sen!2sbd!4v1000000000000!5m2!1sen!2sbd"
                        width="100%" height="400" style="border:0;border-radius:16px" allowfullscreen loading="lazy">
                    </iframe>
                </div>
                <div class="emergency-banner mt-4">
                    <div>🚑 <strong>Emergency Hotline</strong></div>
                    <a href="tel:+8801700000000" class="btn btn-danger btn-lg">Call Now: 01700-000000</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
