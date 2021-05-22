import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ButtonComponent } from './ui/component/button/button.component';
import { ReactiveFormsModule } from '@angular/forms';
import { InputComponent } from './ui/component/input/input.component';
import { AngularSvgIconModule } from 'angular-svg-icon';
import { MatIconModule } from '@angular/material/icon';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';


@NgModule({
  declarations: [
    ButtonComponent,
    InputComponent
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    AngularSvgIconModule,
    MatIconModule,
    MatFormFieldModule,
    MatInputModule
  ],
  exports: [
    InputComponent,
    ButtonComponent
  ]
})
export class SharedModule { }
